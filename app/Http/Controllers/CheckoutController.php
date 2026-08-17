<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Models\Warehouse;
use App\Services\ApiCoIdService;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const SADITA_CITIES = ['makassar', 'maros'];
    private const FREE_SHIPPING_KG = 10;

    public function __construct(
        private CartService $cart,
        private ApiCoIdService $apiCoId
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('toko.katalog')->with('error', 'Keranjang belanja kosong');
        }

        $warehouse = null;
        if ($warehouseId = session('warehouse_id')) {
            $warehouse = Warehouse::query()
                ->where('is_active', true)
                ->where('id', $warehouseId)
                ->first();
        }
        if (! $warehouse) {
            return redirect()->route('toko.home')->with('error', 'Silakan pilih gudang terlebih dahulu sebelum checkout.');
        }

        $provinces = Province::query()->orderBy('name')->get(['id', 'name']);

        return view('toko.checkout', [
            'items' => $this->cart->selectedItems(),
            'subtotal' => $this->cart->subtotal(),
            'totalWeight' => $this->cart->totalWeight(),
            'warehouse' => $warehouse,
            'provinces' => $provinces,
            'cities' => collect(),
            'supportsSubdistrict' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_province' => 'required|exists:provinces,id',
            'customer_regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'customer_city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'customer_address' => 'required|string|max:500',
            'shipping_method' => 'required|string',
            'shipping_service' => 'nullable|string',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|string',
            'voucher_code' => 'nullable|string|max:100',
        ]);

        if ($this->cart->isEmpty()) {
            return redirect()->route('toko.katalog');
        }

        $warehouse = null;
        if ($warehouseId = session('warehouse_id')) {
            $warehouse = Warehouse::query()
                ->where('is_active', true)
                ->where('id', $warehouseId)
                ->first();
        }
        if (! $warehouse) {
            return redirect()->route('toko.home')->with('error', 'Silakan pilih gudang terlebih dahulu sebelum checkout.');
        }

        try {
            $cartItems = $this->cartItemsFromProducts();
            $successToken = Str::random(40);
            $subtotal = (int) $cartItems->sum('subtotal');
            $regency = Regency::query()
                ->where('id', $request->input('customer_regency_id'))
                ->where('province_id', $request->input('customer_province'))
                ->first();

            if (! $regency) {
                throw new \RuntimeException('Kota/kabupaten tidak valid untuk provinsi yang dipilih.');
            }

            $village = Village::query()
                ->with('district')
                ->where('id', $request->input('village_id'))
                ->where('district_id', $request->input('district_id'))
                ->whereHas('district', fn ($query) => $query->where('regency_id', $regency->id))
                ->first();

            if (! $village) {
                throw new \RuntimeException('Kecamatan atau kelurahan/desa tidak valid untuk kota yang dipilih.');
            }
            $weightGrams = $this->cartWeightGrams($cartItems);
            $shippingMethod = $this->resolveShippingMethod($request, $warehouse, $regency, $village, $weightGrams);
            $shippingCost = (int) $shippingMethod['cost'];
            $shippingLabel = $this->shippingLabel($shippingMethod);

            $order = DB::transaction(function () use ($request, $warehouse, $cartItems, $successToken, $shippingCost, $shippingLabel, $shippingMethod, $subtotal, $regency, $village) {
                $productIds = $cartItems->pluck('product_id')->all();

                $stocks = ProductStock::query()
                    ->where('warehouse_id', $warehouse->id)
                    ->whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                foreach ($cartItems as $item) {
                    $stock = $stocks->get($item['product_id']);

                    if (! $stock) {
                        throw new \RuntimeException("Stok produk {$item['name']} tidak tersedia di gudang terpilih.");
                    }

                    $available = max(0, (int) $stock->stock - (int) $stock->reserved_stock);
                    if ($available < (int) $item['quantity']) {
                        throw new \RuntimeException("Stok {$item['name']} tidak cukup. Tersedia {$available} pcs.");
                    }
                }

                // Validate Voucher
                $discountAmount = 0;
                $voucherCode = strtoupper(trim((string) $request->input('voucher_code')));
                if ($voucherCode === 'SADITA2026' && $subtotal >= 100000) {
                    $discountAmount = 20000;
                } elseif ($voucherCode === 'DISKON10') {
                    $discountAmount = min((int) ($subtotal * 0.1), 50000);
                }
                
                $finalTotal = max(0, $subtotal + $shippingCost - $discountAmount);

                $paymentMethodInput = $request->string('payment_method')->toString();
                $paymentMethod = str_contains($paymentMethodInput, '_') ? explode('_', $paymentMethodInput)[0] : $paymentMethodInput;
                if (!in_array($paymentMethod, ['transfer', 'qris', 'cod'])) {
                    throw new \RuntimeException("Metode pembayaran tidak valid.");
                }

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'success_token' => $successToken,
                    'warehouse_id' => $warehouse->id,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $request->customer_address . ' - ' . $village->name . ', ' . $village->district->name . ', ' . $regency->name . ' ' . $request->input('postal_code'),
                    'customer_postal_code' => $request->input('postal_code'),
                    'customer_city' => $regency->name,
                    'province_id' => $regency->province_id,
                    'regency_id' => $regency->id,
                    'district_id' => $village->district_id,
                    'village_id' => $village->id,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'shipping_method' => $shippingLabel,
                    'shipping_courier' => $shippingMethod['name'],
                    'shipping_service' => $shippingMethod['service_code'] ?? null,
                    'shipping_etd' => $shippingMethod['estimated_days'] ?? null,
                    'discount_amount' => $discountAmount,
                    'voucher_code' => $voucherCode ?: null,
                    'cashback_amount' => 0,
                    'total' => $finalTotal,
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'unpaid',
                ]);

                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $stock = $stocks->get($item['product_id']);
                    $stock->increment('reserved_stock', (int) $item['quantity']);
                }

                return $order;
            });

            Log::info("AUDIT LOG: Order created successfully.", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => $order->total,
                'ip_address' => $request->ip(),
            ]);
        } catch (\RuntimeException $e) {
            Log::error("Checkout failed.", [
                'message' => $e->getMessage(),
                'customer_phone' => $request->input('customer_phone'),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }

        // Build WhatsApp message from order snapshot
        $order->loadMissing(['items', 'warehouse']);
        $waMessage = $order->toWhatsAppMessage();

        $recentOrderIds = collect(session('recent_order_ids', []))
            ->prepend($order->id)
            ->unique()
            ->take(20)
            ->values()
            ->all();

        session([
            'checkout_last_order_id' => $order->id,
            'recent_order_ids' => $recentOrderIds,
        ]);
        // User requested: walaupun sudah di checout produk nya maka produk nya masih tetap ada di cart/keranjang
        // $this->cart->clear();

        return redirect()->route('checkout.success', [
            'orderNumber' => $order->order_number,
            'token' => $order->success_token,
        ])
            ->with('wa_message', $waMessage)
            ->with('wa_phone', \App\Models\DisplaySetting::active()->company_whatsapp);
    }

    public function success(string $orderNumber, string $token): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items', 'warehouse'])
            ->firstOrFail();

        $lastCheckoutOrderId = (int) session('checkout_last_order_id');
        $isValidSessionOrder = $lastCheckoutOrderId === (int) $order->id;
        $isValidToken = is_string($order->success_token) && hash_equals($order->success_token, $token);

        abort_unless($isValidToken || $isValidSessionOrder, 403);

        return view('toko.checkout-success', [
            'order' => $order,
            'waMessage' => session('wa_message'),
            'waPhone' => session('wa_phone'),
        ]);
    }

    public function orders(): View
    {
        $recentOrderIds = collect(session('recent_order_ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $orders = Order::query()
            ->with(['items', 'warehouse'])
            ->whereIn('id', $recentOrderIds)
            ->orderByDesc('created_at')
            ->get();

        return view('toko.orders', [
            'orders' => $orders,
            'waPhone' => \App\Models\DisplaySetting::active()->company_whatsapp,
        ]);
    }

    private function cartItemsFromProducts()
    {
        $sessionItems = $this->cart->selectedItems()->values();
        $quantities = $sessionItems
            ->mapWithKeys(fn (array $item) => [(int) $item['product_id'] => max(1, (int) $item['quantity'])]);

        $products = Product::query()
            ->whereIn('id', $quantities->keys()->all())
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        if ($products->count() !== $quantities->count()) {
            throw new \RuntimeException('Sebagian produk di keranjang sudah tidak tersedia. Perbarui keranjang Anda.');
        }

        return $products
            ->map(function (Product $product) use ($quantities) {
                $quantity = (int) $quantities->get($product->id);
                $price = (int) $product->price;

                $actualWeight = (int) ($product->weight ?? 500);
                $volumetricWeight = ($product->length > 0 && $product->width > 0 && $product->height > 0) 
                    ? (int) round(($product->length * $product->width * $product->height) / 6)
                    : 0;
                $chargeableWeight = max($actualWeight, $volumetricWeight);

                return [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                    'unit_weight' => $chargeableWeight,
                ];
            })
            ->values();
    }

    private function cartWeightGrams($cartItems): int
    {
        return (int) $cartItems->sum(function ($item) {
            return ($item['unit_weight'] ?? 500) * $item['quantity'];
        });
    }

    private function resolveShippingMethod(Request $request, Warehouse $warehouse, Regency $regency, Village $village, int $weightGrams): array
    {
        $availableMethods = $this->availableShippingMethods($warehouse, $regency, $village, $weightGrams);

        if ($availableMethods === []) {
            throw new \RuntimeException('Metode pengiriman tidak tersedia untuk alamat ini.');
        }

        $requestedName = trim((string) $request->input('shipping_method'));
        $requestedService = trim((string) $request->input('shipping_service', ''));
        $requestedCost = (int) $request->input('shipping_cost', -1);

        $selected = collect($availableMethods)->first(function (array $method) use ($requestedName, $requestedService) {
            return hash_equals((string) $method['name'], $requestedName)
                && hash_equals((string) ($method['service_code'] ?? ''), $requestedService);
        });

        if (! $selected) {
            throw new \RuntimeException('Metode pengiriman tidak valid. Muat ulang ongkir lalu pilih kembali.');
        }

        if ((int) $selected['cost'] !== $requestedCost) {
            throw new \RuntimeException('Ongkir berubah. Muat ulang ongkir lalu pilih metode pengiriman kembali.');
        }

        return $selected;
    }

    private function availableShippingMethods(Warehouse $warehouse, Regency $regency, Village $village, int $weightGrams): array
    {
        if ($this->isSaditaLocalDestination($regency->name)) {
            $weightKg = $weightGrams / 1000;
            $isFreeShipping = $weightKg >= self::FREE_SHIPPING_KG;

            $methods = [
                [
                    'type' => 'kurir_sadita',
                    'name' => 'Kurir SADITA',
                    'service_code' => 'kurir_sadita',
                    'service_name' => $isFreeShipping
                        ? 'Gratis ongkir pembelian >= ' . self::FREE_SHIPPING_KG . ' kg'
                        : 'Pengiriman dalam kota Makassar & Maros',
                    'cost' => $isFreeShipping ? 0 : 50000,
                    'estimated_days' => '1 - 2 hari kerja',
                    'is_free' => $isFreeShipping,
                ],
                [
                    'type' => 'pickup',
                    'name' => 'Ambil Langsung',
                    'service_code' => 'pickup',
                    'service_name' => 'Ambil sendiri di kantor SADITA',
                    'cost' => 0,
                    'estimated_days' => 'Sesuai jam kantor',
                    'is_free' => true,
                ],
            ];
        } else {
            $methods = $this->apiCoId->getRates(
                $village->id,
                $weightGrams,
                $this->originVillageCode($warehouse)
            );
        }

        // Sort by cheapest, then fastest (keeping pickup at the very end)
        usort($methods, function ($a, $b) {
            $isPickupA = ($a['type'] ?? '') === 'pickup';
            $isPickupB = ($b['type'] ?? '') === 'pickup';
            if ($isPickupA !== $isPickupB) {
                return $isPickupA ? 1 : -1;
            }

            if ($a['cost'] !== $b['cost']) {
                return $a['cost'] <=> $b['cost'];
            }

            $daysA = $this->getMinEstimatedDays($a['estimated_days'] ?? '');
            $daysB = $this->getMinEstimatedDays($b['estimated_days'] ?? '');
            return $daysA <=> $daysB;
        });

        return $methods;
    }

    private function getMinEstimatedDays(string $etd): int
    {
        if (preg_match('/(\d+)/', $etd, $matches)) {
            return (int) $matches[1];
        }
        return 999;
    }

    private function originVillageCode(Warehouse $warehouse): string
    {
        return (string) (
            config('api_co_id.origin_village_codes.' . $warehouse->slug)
            ?: config('api_co_id.origin_village_code')
        );
    }

    private function isSaditaLocalDestination(string $regencyName): bool
    {
        $normalized = strtolower($regencyName);

        foreach (self::SADITA_CITIES as $city) {
            if (str_contains($normalized, $city)) {
                return true;
            }
        }

        return false;
    }

    private function shippingLabel(array $method): string
    {
        $label = (string) $method['name'];
        $service = (string) ($method['service_code'] ?? '');

        if ($service !== '' && ! in_array($service, ['kurir_sadita', 'pickup'], true)) {
            $label .= ' - ' . $service;
        }

        return $label;
    }
}
