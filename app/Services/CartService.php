<?php

namespace App\Services;

use App\Models\ProductUnit;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'sadita_cart';

    public function items(): Collection
    {
        // Abaikan entri lama (pra unit/SKU) yang tidak punya product_unit_id.
        return collect(session(self::SESSION_KEY, []))
            ->filter(fn ($item) => ! empty($item['product_unit_id']));
    }

    public function add(ProductUnit $unit, int $quantity = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $id = $unit->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $unit->loadMissing('product');

            $cart[$id] = [
                'product_unit_id' => $unit->id,
                'product_id' => $unit->product_id,
                'name' => $unit->fullName(),
                'slug' => $unit->slug,
                'price' => (int) $unit->price,
                'image' => $unit->product?->image,
                'quantity' => $quantity,
                'unit_weight' => $unit->chargeableWeight(),
            ];
        }

        $cart[$id]['subtotal'] = $cart[$id]['price'] * $cart[$id]['quantity'];
        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $key, int $quantity): void
    {
        $cart = session(self::SESSION_KEY, []);

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                $this->remove($key);
                return;
            }
            $cart[$key]['quantity'] = $quantity;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $quantity;
            session([self::SESSION_KEY => $cart]);
        }
    }

    public function remove(int $key): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$key]);
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
