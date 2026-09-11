<?php
$file = 'app/Http/Controllers/CheckoutController.php';
$content = file_get_contents($file);

// Replace index method
$indexFind = <<<PHP
    public function index(): View|RedirectResponse
    {
        \$warehouse = Warehouse::find(session('warehouse_id'));

        if (! \$warehouse) {
            return redirect()->route('toko.home')->with('error', 'Silakan pilih gudang terlebih dahulu.');
        }

        if (\$this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        \$items = \$this->cartItemsFromUnits();
        \$subtotal = \$items->sum('subtotal');
        \$totalWeight = \$this->cartWeightGrams(\$items);

        return view('toko.checkout', [
            'warehouse' => \$warehouse,
            'items' => \$items,
            'subtotal' => \$subtotal,
            'totalWeight' => \$totalWeight,
        ]);
    }
PHP;

$indexReplace = <<<PHP
    public function index(Request \$request): View|RedirectResponse
    {
        \$warehouse = Warehouse::find(session('warehouse_id'));

        if (! \$warehouse) {
            return redirect()->route('toko.home')->with('error', 'Silakan pilih gudang terlebih dahulu.');
        }

        if (\$this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        \$items = \$this->cartItemsFromUnits();
        
        \$selectedItems = \$request->query('items');
        if (\$selectedItems) {
            \$selectedIds = explode(',', \$selectedItems);
            \$items = \$items->filter(function(\$item) use (\$selectedIds) {
                return in_array((string)\$item['product_unit_id'], \$selectedIds);
            })->values();
            
            if (\$items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak valid.');
            }
        }

        \$subtotal = \$items->sum('subtotal');
        \$totalWeight = \$this->cartWeightGrams(\$items);

        return view('toko.checkout', [
            'warehouse' => \$warehouse,
            'items' => \$items,
            'subtotal' => \$subtotal,
            'totalWeight' => \$totalWeight,
            'selectedItemsStr' => \$selectedItems,
        ]);
    }
PHP;

$content = str_replace($indexFind, $indexReplace, $content);

// In store method, we also need to get the selected items
$storeFind = <<<PHP
    public function store(Request \$request): RedirectResponse
    {
        \$warehouse = Warehouse::find(session('warehouse_id'));
        abort_unless((bool) \$warehouse, 400, 'Gudang belum dipilih.');

        if (\$this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        \$validated = \$request->validate([
PHP;

$storeReplace = <<<PHP
    public function store(Request \$request): RedirectResponse
    {
        \$warehouse = Warehouse::find(session('warehouse_id'));
        abort_unless((bool) \$warehouse, 400, 'Gudang belum dipilih.');

        if (\$this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        \$validated = \$request->validate([
            'selected_items_str' => 'nullable|string',
PHP;

$content = str_replace($storeFind, $storeReplace, $content);

// Update store to filter items
$storeItemsFind = <<<PHP
        \$items = \$this->cartItemsFromUnits();
        \$subtotal = \$items->sum('subtotal');
        \$totalWeight = \$this->cartWeightGrams(\$items);
PHP;

$storeItemsReplace = <<<PHP
        \$items = \$this->cartItemsFromUnits();
        
        \$selectedItemsStr = \$request->input('selected_items_str');
        if (\$selectedItemsStr) {
            \$selectedIds = explode(',', \$selectedItemsStr);
            \$items = \$items->filter(function(\$item) use (\$selectedIds) {
                return in_array((string)\$item['product_unit_id'], \$selectedIds);
            })->values();
            
            if (\$items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak valid.');
            }
        }
        
        \$subtotal = \$items->sum('subtotal');
        \$totalWeight = \$this->cartWeightGrams(\$items);
PHP;

$content = str_replace($storeItemsFind, $storeItemsReplace, $content);

// Now remove the clear cart
$clearFind = <<<PHP
        session([
            'checkout_last_order_id' => \$order->id,
            'recent_order_ids' => \$recentOrderIds,
        ]);
        \$this->cart->clear();

        return redirect()->route('checkout.success', [
PHP;

$clearReplace = <<<PHP
        session([
            'checkout_last_order_id' => \$order->id,
            'recent_order_ids' => \$recentOrderIds,
        ]);
        // USER REQUEST: Jangan clear cart meskipun sudah checkout (sifatnya tersimpan sampai dihapus)
        // \$this->cart->clear();

        return redirect()->route('checkout.success', [
PHP;

$content = str_replace($clearFind, $clearReplace, $content);

file_put_contents($file, $content);
?>
