<?php
$file = 'resources/views/toko/cart.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    '<section class="px-5 py-4 space-y-3">',
    '<section class="px-5 py-2 flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm font-bold text-ink cursor-pointer">
                <input type="checkbox" id="select-all" class="w-5 h-5 text-primary rounded border-line focus:ring-primary" checked onchange="toggleAll(this)">
                Pilih Semua
            </label>
        </section>
        <section class="px-5 pb-4 space-y-3">',
    $content
);

$content = str_replace(
    '<div class="flex gap-3 rounded-2xl border border-line bg-white p-3" id="cart-item-{{ $itemKey }}">',
    '<div class="flex gap-3 rounded-2xl border border-line bg-white p-3 items-center" id="cart-item-{{ $itemKey }}">
                    <input type="checkbox" name="selected_items[]" value="{{ $itemKey }}" data-price="{{ $item[\'price\'] }}" class="cart-item-checkbox w-5 h-5 text-primary rounded border-line focus:ring-primary shrink-0" checked onchange="updateTotalClientSide()">',
    $content
);

$content = str_replace(
    '<a href="{{ route(\'checkout\') }}" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Proses Pesanan
            </a>',
    '<button onclick="processCheckout()" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Proses Pesanan
            </button>',
    $content
);

$js = <<<JS
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateTotalClientSide();
        }

        function updateTotalClientSide() {
            let total = 0;
            document.querySelectorAll('.cart-item-checkbox:checked').forEach(cb => {
                const itemKey = cb.value;
                const qtyText = document.getElementById('qty-' + itemKey).textContent;
                const price = parseFloat(cb.getAttribute('data-price'));
                total += price * parseInt(qtyText);
            });
            document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
            
            const allCheckboxes = document.querySelectorAll('.cart-item-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            document.getElementById('select-all').checked = (allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length);
        }

        function processCheckout() {
            const checked = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(cb => cb.value);
            if (checked.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Pilih Produk', 'Silakan pilih minimal satu produk untuk di-checkout.', 'warning');
                } else {
                    alert('Silakan pilih minimal satu produk untuk di-checkout.');
                }
                return;
            }
            window.location.href = "{{ route('checkout') }}?items=" + checked.join(',');
        }
JS;

$content = str_replace(
    '<script>',
    '<script>
' . $js,
    $content
);

// We must also update `updateQty` to call `updateTotalClientSide()` instead of just replacing the total with the backend response (since backend calculates for all items)
$content = str_replace(
    "document.getElementById('total-price').textContent = 'Rp ' + Number(data.subtotal || 0).toLocaleString('id-ID');",
    "updateTotalClientSide();",
    $content
);

file_put_contents($file, $content);
?>
