<script>
    if (!window.saditaCartScriptLoaded) {
    window.saditaCartScriptLoaded = true;

    // Update cart count badge dari server
    window.saditaUpdateCartCount = function saditaUpdateCartCount() {
        fetch('/cart/count', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                document.querySelectorAll('[data-cart-count]').forEach(badge => {
                    badge.textContent = data.count || 0;
                    badge.style.display = data.count > 0 ? 'flex' : 'none';
                });
            })
            .catch(() => {}); // silent fail
    };

    window.saditaAddToCart = function saditaAddToCart(product, qty = 1) {
        // Find product by slug via add route
        return fetch(`/cart/add-by-slug/${product.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) throw data;
            return data;
        })
        .then(data => {
            saditaUpdateCartCount();
            if (typeof window.saditaNotify === 'function') {
                window.saditaNotify(data.message || 'Ditambahkan ke keranjang', 'success');
                return data;
            }
            const toast = document.getElementById('cart-toast');
            if (toast) {
                toast.textContent = data.message || 'Ditambahkan ke keranjang';
                toast.classList.remove('hidden', 'opacity-0');
                toast.classList.add('opacity-100');
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => toast.classList.add('hidden'), 300);
                }, 2000);
            }
            return data;
        })
        .catch((err) => {
            if (typeof window.saditaNotify === 'function') {
                window.saditaNotify(err?.message || 'Gagal menambahkan ke keranjang', 'error');
                throw err;
            }
            const toast = document.getElementById('cart-toast');
            if (toast) {
                toast.textContent = err?.message || 'Gagal menambahkan ke keranjang';
                toast.classList.remove('hidden', 'opacity-0');
                toast.classList.add('opacity-100');
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => toast.classList.add('hidden'), 300);
                }, 2000);
            }
            throw err;
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        saditaUpdateCartCount();
        document.querySelectorAll('.add-cart[data-product]').forEach(button => {
            if (button.dataset.cartBound === '1') return;
            button.dataset.cartBound = '1';
            button.addEventListener('click', () => {
                const product = JSON.parse(button.dataset.product);
                saditaAddToCart(product);
                const original = button.innerHTML;
                button.innerHTML = '<span class="material-symbols-outlined text-[17px]">check</span>Ditambahkan';
                setTimeout(() => button.innerHTML = original, 900);
            });
        });
    });
    }
</script>
