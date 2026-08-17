<?php

namespace App\Http\Controllers;

use App\Models\DisplaySetting;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return view('toko.home', [
            'displaySetting' => DisplaySetting::active(),
            'warehouses' => Warehouse::query()
                ->where('is_active', true)
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function select(Request $request, Warehouse $warehouse, \App\Services\CartService $cartService): RedirectResponse
    {
        $oldWarehouseId = session('warehouse_id');
        
        session([
            'warehouse_id' => $warehouse->id,
            'selected_warehouse_name' => $warehouse->name,
            'selected_warehouse' => $warehouse->slug,
        ]);

        if ($oldWarehouseId && $oldWarehouseId !== $warehouse->id && !$cartService->isEmpty()) {
            $items = $cartService->items();
            $changed = false;

            foreach ($items as $item) {
                $stock = \App\Models\ProductStock::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $warehouse->id)
                    ->first();

                $available = $stock ? max(0, (int) $stock->stock - (int) $stock->reserved_stock) : 0;

                if ($available < $item['quantity']) {
                    $cartService->update($item['product_id'], $available);
                    $changed = true;
                }
            }

            if ($changed) {
                return redirect()->route('toko.katalog')->with('error', 'Beberapa barang di keranjang Anda disesuaikan karena stok tidak cukup di gudang baru.');
            }
        }

        return redirect()->route('toko.katalog');
    }
}
