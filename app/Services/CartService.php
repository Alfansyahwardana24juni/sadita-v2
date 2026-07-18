<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'sadita_cart';

    public function items(): Collection
    {
        return collect(session(self::SESSION_KEY, []));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $actualWeight = (int) ($product->weight ?? 500);
            $volumetricWeight = ($product->length > 0 && $product->width > 0 && $product->height > 0) 
                ? (int) round(($product->length * $product->width * $product->height) / 6)
                : 0;
            $chargeableWeight = max($actualWeight, $volumetricWeight);

            $cart[$id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'unit_weight' => $chargeableWeight,
            ];
        }

        $cart[$id]['subtotal'] = $cart[$id]['price'] * $cart[$id]['quantity'];
        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = session(self::SESSION_KEY, []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                $this->remove($productId);
                return;
            }
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $quantity;
            session([self::SESSION_KEY => $cart]);
        }
    }

    public function remove(int $productId): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return $this->items()->sum('quantity');
    }

    public function totalWeight(): int
    {
        return (int) $this->items()->sum(function ($item) {
            return ($item['unit_weight'] ?? 500) * $item['quantity'];
        });
    }

    public function subtotal(): int
    {
        return (int) $this->items()->sum('subtotal');
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    public function toWhatsAppMessage(array $customerData, ?string $warehouseName = null): string
    {
        $items = $this->items();
        $lines = ["*Pesanan Baru - SADITA*", ""];

        if ($warehouseName) {
            $lines[] = "📦 Gudang: {$warehouseName}";
        }

        $lines[] = "👤 Nama: {$customerData['name']}";
        $lines[] = "📱 HP: {$customerData['phone']}";
        if (!empty($customerData['address'])) {
            $lines[] = "📍 Alamat: {$customerData['address']}";
        }
        $lines[] = "";
        $lines[] = "*Detail Pesanan:*";

        foreach ($items as $item) {
            $subtotal = 'Rp ' . number_format($item['subtotal'], 0, ',', '.');
            $lines[] = "- {$item['name']} x{$item['quantity']} = {$subtotal}";
        }

        $lines[] = "";
        $lines[] = "*Total: Rp " . number_format($this->subtotal(), 0, ',', '.') . "*";

        if (!empty($customerData['notes'])) {
            $lines[] = "";
            $lines[] = "📝 Catatan: {$customerData['notes']}";
        }

        return implode("\n", $lines);
    }
}
