<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const MAX_QTY = 20;

    public function __construct(private CartService $cart) {}

    public function add(Request $request, ProductUnit $productUnit): JsonResponse|RedirectResponse
    {
        $quantity = (int) $request->input('quantity', 1);
        $quantity = min(self::MAX_QTY, max(1, $quantity));

        if (! $productUnit->is_active) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unit produk tidak tersedia'], 422);
            }
            return back()->with('error', 'Unit produk tidak tersedia');
        }

        $warehouseId = session('warehouse_id');
        if ($warehouseId) {
            $stock = \App\Models\ProductStock::where('product_unit_id', $productUnit->id)
                ->where('warehouse_id', $warehouseId)
                ->first();

            $available = $stock ? max(0, (int) $stock->stock - (int) $stock->reserved_stock) : 0;
            if ($available < $quantity) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Stok tidak mencukupi di gudang terpilih'], 422);
                }
                return back()->with('error', 'Stok tidak mencukupi di gudang terpilih');
            }
        }

        $this->cart->add($productUnit, $quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'count' => $this->cart->count(),
                'message' => 'Produk ditambahkan ke keranjang',
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request, int $key): JsonResponse
    {
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity > 0) {
            $quantity = min(self::MAX_QTY, $quantity);
        }
        $this->cart->update($key, $quantity);

        return response()->json([
            'count' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
            'totalWeight' => $this->cart->totalWeight(),
            'items' => $this->cart->items()->values(),
        ]);
    }

    public function remove(int $key): JsonResponse
    {
        $this->cart->remove($key);

        return response()->json([
            'count' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
            'totalWeight' => $this->cart->totalWeight(),
        ]);
    }

    public function index(CartService $cart)
    {
        return view('toko.cart', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'warehouseName' => session('selected_warehouse_name'),
        ]);
    }

    public function count(CartService $cart): JsonResponse
    {
        return response()->json(['count' => $cart->count()]);
    }
}
