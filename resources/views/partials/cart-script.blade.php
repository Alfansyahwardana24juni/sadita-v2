<script>
    if (!window.saditaCartScriptLoaded) {
    window.saditaCartScriptLoaded = true;

    const CART_STORAGE_KEY = 'sadita_cart_v1';

    window.saditaGetLocalCart = function() {
        try {
            const raw = localStorage.getItem(CART_STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    };

    window.saditaSaveLocalCart = function(items) {
        try {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items || []));
        } catch (e) {}
    };

    window.saditaUpdateCartCount = function(forcedCount) {
        if (typeof forcedCount === 'number') {
            document.querySelectorAll('[data-cart-count]').forEach(badge => {
                badge.textContent = forcedCount;
                badge.style.display = forcedCount > 0 ? 'flex' : 'none';
            });
            return;
        }

        fetch('/cart/count', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                const count = typeof data.count === 'number' ? data.count : 0;
                document.querySelectorAll('[data-cart-count]').forEach(badge => {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            })
            .catch(() => {
                const local = window.saditaGetLocalCart();
                const count = local.reduce((acc, it) => acc + (parseInt(it.quantity) || 1), 0);
                document.querySelectorAll('[data-cart-count]').forEach(badge => {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            });
    };

    window.saditaSyncCart = async function() {
        const local = window.saditaGetLocalCart();
        try {
            const res = await fetch('/cart/sync', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ items: local })
            });
            if (res.ok) {
                const data = await res.json();
                if (Array.isArray(data.items)) {
                    window.saditaSaveLocalCart(data.items);
                }
                window.saditaUpdateCartCount(data.count);

                // If on /cart and server previously had 0 items but restored items > 0, reload to show items
                if (window.location.pathname.endsWith('/cart') && local.length > 0 && document.querySelector('#select-all') === null) {
                    window.location.reload();
                }
            } else {
                window.saditaUpdateCartCount();
            }
        } catch (e) {
            window.saditaUpdateCartCount();
        }
    };

    window.saditaAddToCart = function(product, qty = 1) {
        const quantity = Math.max(1, parseInt(qty) || 1);
        
        // Optimistically update localStorage
        const local = window.saditaGetLocalCart();
        const unitId = product.product_unit_id || product.unit_id || product.id;
        const existingIdx = local.findIndex(i => (i.product_unit_id == unitId) || (i.id == unitId) || (i.slug && product.id && i.slug == product.id));
        if (existingIdx >= 0) {
            local[existingIdx].quantity = (parseInt(local[existingIdx].quantity) || 0) + quantity;
        } else {
            local.push({
                product_unit_id: product.product_unit_id || product.unit_id || null,
                id: product.id,
                slug: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: quantity
            });
        }
        window.saditaSaveLocalCart(local);

        const identifier = product.id || product.product_unit_id || product.unit_id;
        return fetch(`/cart/add-by-slug/${identifier}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) throw data;
            return data;
        })
        .then(data => {
            if (typeof data.count === 'number') {
                window.saditaUpdateCartCount(data.count);
            } else {
                window.saditaUpdateCartCount();
            }

            if (typeof window.saditaNotify === 'function') {
                window.saditaNotify(data.message || 'Ditambahkan ke keranjang', 'cart');
            } else {
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
            }
            return data;
        })
        .catch((err) => {
            window.saditaUpdateCartCount();
            const msg = err?.message || 'Gagal menambahkan ke keranjang';
            if (typeof window.saditaNotify === 'function') {
                window.saditaNotify(msg, 'error');
            } else {
                const toast = document.getElementById('cart-toast');
                if (toast) {
                    toast.textContent = msg;
                    toast.classList.remove('hidden', 'opacity-0');
                    toast.classList.add('opacity-100');
                    setTimeout(() => {
                        toast.classList.add('opacity-0');
                        setTimeout(() => toast.classList.add('hidden'), 300);
                    }, 2000);
                }
            }
            throw err;
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.saditaSyncCart();

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
