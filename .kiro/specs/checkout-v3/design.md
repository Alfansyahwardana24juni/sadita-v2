# Design Document: Checkout V3

## Overview

Checkout V3 adalah pengembangan menyeluruh halaman checkout SADITA menggunakan **Livewire + Alpine.js + Tailwind CSS** di atas Laravel. Desain ini menggantikan checkout lama dengan arsitektur yang lebih modular, aman, dan siap dikembangkan untuk roadmap V3.1–V3.7.

**Target:** checkout selesai < 60 detik, ongkir akurat, tidak ada manipulasi harga dari sisi client, siap mendukung multi-warehouse dan payment gateway di masa depan.

**Stack:** Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS (design system SADITA)

**Route baru:** `GET /checkout-v3` dan `POST /checkout-v3/store` — berjalan paralel dengan route lama selama masa transisi.

---

## Architecture

### Diagram Tingkat Tinggi

```
Browser (Alpine.js)
    │  wire:model / wire:click
    ▼
Livewire Component: CheckoutV3Page
    │   constructor injection
    ├──► ShippingService  ──► ApiCoIdService  ──► API.co.id
    │        │                                   (cache 10 menit)
    │        └──► SADITA Courier logic (lokal)
    │
    └── submit checkout
            │
            ▼
    CheckoutV3Controller
            │  DB::transaction
            ▼
    OrderService
        ├── validasi harga (vs database)
        ├── validasi stok (vs product_stocks)
        ├── kalkulasi ulang ongkir (via ShippingService)
        ├── buat Order + OrderItems
        ├── kurangi stok
        └── generate invoice & success_token
            │
            ▼
    Redirect → /checkout/success/{token}
```


### Pola Arsitektur

- **Single Livewire Component** sebagai orkestrator state. Semua reaktivitas dikelola di sini.
- **Blade Partials** untuk setiap section (1-7) agar setiap bagian dapat dikembangkan secara terpisah tanpa menyentuh component utama.
- **Service Layer** (`ShippingService`, `OrderService`) terpisah dari component, dapat diuji independen via PHPUnit.
- **Backend sebagai satu-satunya sumber kebenaran**: semua harga, berat, stok, dan ongkir di-validasi ulang di server sebelum order dibuat.
- **Config-driven UI**: metode pembayaran dan status voucher dirender dari array konfigurasi, bukan hardcode di template.

---

## Components and Interfaces

### Livewire Component

**File:** `app/Livewire/CheckoutV3Page.php`

```
CheckoutV3Page
├── Properties (reactive)
│   ├── Alamat: name, phone, address, postal_code, notes
│   ├── Region IDs: provinceId, regencyId, districtId, villageId
│   ├── Dropdown options: provinces[], regencies[], districts[], villages[]
│   ├── Shipping: shippingMethods[], selectedShipping, isCalculatingShipping
│   ├── Payment: selectedPayment
│   ├── Voucher: voucherCode
│   ├── State: isSubmitting, errors[], showAllShipping
│   └── Computed: subtotal, totalWeight, discount, shippingCost, grandTotal
│
├── Lifecycle
│   └── mount(): load provinces, prefill user profile jika login
│
├── Event Handlers
│   ├── updatedProvinceId(): load regencies, reset cascading fields
│   ├── updatedRegencyId(): load districts, reset
│   ├── updatedDistrictId(): load villages, reset
│   ├── updatedVillageId(): trigger calculateShipping()
│   ├── selectShipping($key): set selectedShipping, update shippingCost
│   ├── selectPayment($method): set selectedPayment
│   └── submitCheckout(): validasi → OrderService → redirect
│
└── Private Methods
    ├── calculateShipping(): panggil ShippingService, handle cache
    ├── validateForm(): validasi client-side sebelum submit
    └── prepareOrderData(): kumpulkan data untuk OrderService
```


### Blade Partials

**Directory:** `resources/views/livewire/checkout-v3/`

| File | Section | Deskripsi |
|---|---|---|
| `checkout-v3-page.blade.php` | Root | Template utama, memuat semua partial |
| `partials/_section-address.blade.php` | 1 | Form alamat pengiriman + validasi |
| `partials/_section-shipping.blade.php` | 2 | Card metode pengiriman + skeleton loading |
| `partials/_section-summary.blade.php` | 3 | Ringkasan belanja + detail produk |
| `partials/_section-voucher.blade.php` | 4 | Input voucher (disabled untuk V3.0) |
| `partials/_section-payment.blade.php` | 5 | Card metode pembayaran |
| `partials/_section-total.blade.php` | 6 | Rincian total pembayaran |
| `partials/_sticky-bar.blade.php` | - | Bottom bar sticky (total + tombol checkout) |
| `partials/_skeleton-shipping.blade.php` | - | Skeleton loading untuk card pengiriman |

### Service Classes

**`app/Services/ShippingService.php`** (sudah ada, akan di-upgrade):

```
ShippingService
├── getAvailableShippingMethods(villageCode, regencyName, weightGrams, warehouseId): array
├── isSaditaCity(regencyName): bool
├── sortShippingMethods(methods): array  // murah → etd → alfabetis
└── private
    ├── getLocalRates(isFree, weightGrams): array  // Kurir SADITA logic
    ├── getApiCoIdRates(villageCode, weightGrams, warehouseId): array
    └── buildCacheKey(villageCode, weightGrams, warehouseId): string
```

**`app/Services/OrderService.php`** (baru):

```
OrderService
├── createOrder(array $data, array $cartItems): Order
│   └── (dijalankan dalam DB::transaction)
├── private validatePrices(array $cartItems): array  // vs DB prices
├── private validateStock(array $cartItems, int $warehouseId): void
├── private recalculateShipping(array $addressData, int $weightGrams): int
├── private createOrderRecord(array $data): Order
├── private createOrderItems(Order $order, array $cartItems): void
├── private deductStock(Order $order, int $warehouseId): void
└── private generateSuccessToken(): string
```

**`app/Http/Controllers/CheckoutV3Controller.php`** (baru):

```
CheckoutV3Controller
├── index(CartService): View           // GET /checkout-v3
└── store(CheckoutV3Request): RedirectResponse  // POST /checkout-v3/store
```

---

## Data Models

### Tabel `orders` — Kolom Yang Perlu Ditambahkan

Migrasi baru: `2026_07_XX_000001_add_checkout_v3_fields_to_orders_table.php`

```php
// Kolom shipping detail (menggantikan shipping_method single column)
$table->string('shipping_courier')->nullable()->after('shipping_method');
$table->string('shipping_service')->nullable()->after('shipping_courier');
$table->string('shipping_etd')->nullable()->after('shipping_service');
// shipping_cost sudah ada

// Kolom voucher dan diskon (future-ready)
$table->integer('discount_amount')->default(0)->after('shipping_cost');
$table->string('voucher_code')->nullable()->after('discount_amount');
$table->integer('cashback_amount')->default(0)->after('voucher_code');

// customer_postal_code
$table->string('customer_postal_code', 10)->nullable()->after('customer_address');
```


### Skema Lengkap Tabel `orders` (setelah migrasi)

```
orders
├── id                    bigint PK
├── order_number          varchar(20) UNIQUE  -- "SDTyymmdNNNN"
├── success_token         varchar(64) UNIQUE  -- untuk akses halaman success
├── warehouse_id          FK → warehouses
├── user_id               FK → users (nullable, guest checkout)
├── customer_name         varchar(100)
├── customer_phone        varchar(20)
├── customer_address      text
├── customer_postal_code  varchar(10)
├── customer_city         varchar(100)        -- snapshot nama kota
├── province_id           char(2) FK → provinces
├── regency_id            char(4) FK → regencies
├── district_id           char(7) FK → districts
├── village_id            char(10) FK → villages
├── subtotal              integer             -- sum(price × qty) semua item
├── shipping_cost         integer
├── shipping_method       varchar(50)         -- "kurir_sadita" | "api_co_id" | "pickup"
├── shipping_courier      varchar(50)         -- "JNE" | "TIKI" | "SADITA" dll
├── shipping_service      varchar(50)         -- "REG" | "OKE" dll
├── shipping_etd          varchar(30)         -- "2-3 hari"
├── discount_amount       integer DEFAULT 0
├── voucher_code          varchar(50) nullable
├── cashback_amount       integer DEFAULT 0
├── total                 integer             -- subtotal + shipping_cost - discount_amount
├── status                enum(pending,confirmed,processing,shipped,delivered,cancelled)
├── payment_method        varchar(30)         -- "transfer" | "qris" | "cod"
├── payment_status        varchar(30)         -- "unpaid" | "paid_pending_verify" | ...
├── notes                 text nullable
├── admin_notes           text nullable
└── timestamps
```

### Tabel `order_items` (sudah memadai, tidak ada perubahan)

```
order_items
├── id
├── order_id    FK → orders (cascade delete)
├── product_id  FK → products (null on delete — snapshot)
├── product_name  varchar  -- snapshot nama saat transaksi
├── product_sku   varchar  -- snapshot SKU
├── quantity      integer
├── price         integer  -- harga satuan SAAT transaksi (snapshot)
├── subtotal      integer  -- price × quantity SAAT transaksi
└── timestamps
```

**Catatan penting:** `price` dan `subtotal` di `order_items` adalah snapshot harga saat transaksi. Perubahan harga produk di tabel `products` tidak mempengaruhi order yang sudah ada.

### Model Updates

`app/Models/Order.php` — tambahkan fillable dan relasi baru:

```php
protected $fillable = [
    // ... existing fields ...
    'shipping_courier', 'shipping_service', 'shipping_etd',
    'discount_amount', 'voucher_code', 'cashback_amount',
    'customer_postal_code',
];
```

---

## Flow Kalkulasi Ongkir

### Diagram Alur

```
Pemilihan Kelurahan
        │
        ▼
isCalculatingShipping = true
Tampilkan skeleton loading
        │
        ▼
ShippingService::getAvailableShippingMethods(
    villageCode, regencyName, weightGrams, warehouseId
)
        │
        ├─ [Cache HIT] ──────────────────────────► return cached result
        │
        └─ [Cache MISS]
                │
                ├─ isSaditaCity(regencyName)?
                │       │
                │       ├── YES (Makassar/Maros)
                │       │       ├── Add: Kurir SADITA (free jika ≥ 10kg)
                │       │       └── Add: Ambil Langsung
                │       │
                │       └── NO (luar kota)
                │               ├── Call ApiCoIdService::getRates(villageCode, weightGrams)
                │               │       └── (timeout: 15 detik, error → return [])
                │               └── Add: Ambil Langsung
                │
                ├── sortShippingMethods() — murah → etd asc → alfa
                ├── Cache::put(key, result, TTL=600)
                └── return result
        │
        ▼
isCalculatingShipping = false
Render card metode pengiriman
(default: tampilkan satu terbaik)
```


### Cache Key dan TTL

```php
// Format cache key:
"shipping_v3:{$warehouseId}:{$villageCode}:{$weightKg}"

// Contoh:
"shipping_v3:1:7371010001:2"  // warehouse 1, kelurahan code, 2 kg

// TTL: 600 detik (10 menit)
// Driver: default cache driver dari config/cache.php
```

**Alasan TTL 10 menit:** harga ongkir tidak berubah sesering data stok. 10 menit memberikan keseimbangan antara akurasi dan performa. Perubahan metode pembayaran tidak trigger ulang kalkulasi ongkir.

### Logika SADITA Courier

```
isSaditaCity(regencyName):
  normalize = lowercase(trim(regencyName))
  return "makassar" ∈ normalize OR "maros" ∈ normalize

Kurir SADITA entry:
  isFree = weightGrams >= 10_000
  cost   = isFree ? 0 : 50_000  (nilai default, configurable via .env)
  badge  = isFree ? "Gratis" : null
```

### Sorting Algorithm

```
sortShippingMethods(methods[]):
  1. Primary sort: cost ASC
  2. Secondary sort (tie): estimated_days_lower_bound ASC
     (parse "2-3 hari" → lower bound = 2)
  3. Tertiary sort (tie): name ASC (alfabetis)
```

---

## Flow Pembuatan Order

### Diagram Alur OrderService

```
submitCheckout() di Livewire
        │
        ▼
[1] Validasi form di Livewire (client-side rules)
        │ GAGAL → tampilkan error, scroll ke error pertama
        ▼
[2] isSubmitting = true, tombol disabled
        │
        ▼
[3] HTTP POST ke CheckoutV3Controller::store()
        │
        ▼
[4] FormRequest validation (server-side, CSRF + rate limit)
        │ GAGAL → 422/429 → Livewire handle error
        ▼
[5] DB::transaction() {
        │
        ├── [5a] Load cart dari session
        │
        ├── [5b] Load harga produk dari database
        │         Validasi: harga_db == harga_client? TIDAK → rollback, return 422
        │
        ├── [5c] Verifikasi stok di warehouse
        │         product_stocks WHERE product_id IN (...) AND warehouse_id = X
        │         Jika qty > available_stock → rollback, return 422
        │
        ├── [5d] Kalkulasi ulang ongkir di server
        │         ShippingService::getAvailableShippingMethods(...)
        │         Cari metode yang dipilih, ambil cost
        │         Jika cost berbeda > threshold → rollback, return 422
        │
        ├── [5e] Buat record Order
        │         Order::create([...])
        │
        ├── [5f] Buat record OrderItems
        │         foreach cartItems → OrderItem::create([...])
        │
        ├── [5g] Kurangi stok
        │         ProductStock::where(...)->decrement('stock', qty)
        │
        └── [5h] Generate success_token
                  bin2hex(random_bytes(16)) → 32 char hex
    } // end transaction
        │
        ▼
[6] Clear cart session
        │
        ▼
[7] Redirect ke /checkout/success/{order_number}/{success_token}
```

### Rollback Strategy

Karena semua operasi database ada di dalam `DB::transaction()`, jika terjadi exception di langkah manapun (5b–5h), Laravel otomatis me-rollback semua perubahan. Controller mengembalikan response 422 dengan pesan error yang sesuai.

---

## State Management di Livewire

### Reactive Properties

```php
// Alamat
public string $name = '';
public string $phone = '';
public string $address = '';
public string $postalCode = '';
public string $notes = '';

// Region IDs (string karena ID wilayah bisa "01", "0101", dll)
public string $provinceId = '';
public string $regencyId = '';
public string $districtId = '';
public string $villageId = '';

// Dropdown options (array of ['id' => ..., 'name' => ...])
public array $provinces = [];
public array $regencies = [];
public array $districts = [];
public array $villages = [];

// Shipping state
public array $shippingMethods = [];
public ?array $selectedShipping = null;
public bool $isCalculatingShipping = false;
public bool $shippingError = false;
public string $shippingErrorMessage = '';
public bool $showAllShipping = false;

// Payment
public string $selectedPayment = '';

// Voucher
public string $voucherCode = '';

// Order submission
public bool $isSubmitting = false;
public array $errors = [];
```


### Computed Values

```php
// Dihitung dari cart session (tidak disimpan sebagai property)
public function getSubtotalProperty(): int
{
    return collect(session('sadita_cart', []))->sum('subtotal');
}

public function getTotalWeightProperty(): int
{
    // sum(product.weight_grams * qty) — diambil dari DB saat mount
    return $this->cartWeightGrams;
}

public function getShippingCostProperty(): int
{
    return $this->selectedShipping['cost'] ?? 0;
}

public function getDiscountProperty(): int
{
    // V3.0: selalu 0. V3.1: dari voucher service
    return 0;
}

public function getGrandTotalProperty(): int
{
    return $this->subtotal + $this->shippingCost - $this->discount;
}
```

### Cascading Dropdown Update Pattern

```php
public function updatedProvinceId(): void
{
    $this->regencies = Regency::where('province_id', $this->provinceId)
        ->orderBy('name')->get(['id', 'name'])->toArray();
    // Reset cascading
    $this->regencyId = '';
    $this->districtId = '';
    $this->villageId = '';
    $this->districts = [];
    $this->villages = [];
    $this->shippingMethods = [];
    $this->selectedShipping = null;
}
// Pattern serupa untuk updatedRegencyId() dan updatedDistrictId()

public function updatedVillageId(): void
{
    if ($this->villageId) {
        $this->calculateShipping();
    }
}
```

---

## Breakdown Komponen UI

### Section 1: Alamat Pengiriman

- Grid form 2 kolom (Nama + WhatsApp) di atas, kolom penuh untuk alamat
- Setiap field: `<label for="X">Label <span>*</span></label><input id="X" wire:model="...">`
- Dropdown region menggunakan `wire:model.live` untuk cascading otomatis
- Loading state dropdown: `wire:loading.attr="disabled"` pada select dan `wire:target="updatedProvinceId"` dll
- Error message: `@error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror`

### Section 2: Metode Pengiriman

```html
<!-- Skeleton loading saat isCalculatingShipping = true -->
<div wire:loading wire:target="calculateShipping">
    @include('livewire.checkout-v3.partials._skeleton-shipping')
</div>

<!-- Card hasil kalkulasi -->
<div wire:loading.remove wire:target="calculateShipping">
    @foreach($shippingMethods as $key => $method)
        @if($loop->first || $showAllShipping)
            <button wire:click="selectShipping('{{ $key }}')">
                <!-- nama kurir, service, etd, harga, badge gratis -->
            </button>
        @endif
    @endforeach
    @if(count($shippingMethods) > 1)
        <button wire:click="$set('showAllShipping', !$showAllShipping)">
            Lihat layanan lain
        </button>
    @endif
</div>
```

### Section 5: Metode Pembayaran (Config-Driven)

```php
// config/sadita.php
'payment_methods' => [
    ['key' => 'transfer', 'icon' => 'account_balance', 'label' => 'Transfer Bank',  'sublabel' => 'Transfer Manual'],
    ['key' => 'qris',     'icon' => 'qr_code_2',       'label' => 'QRIS',           'sublabel' => 'Semua E-Wallet'],
    ['key' => 'cod',      'icon' => 'payments',         'label' => 'COD',            'sublabel' => 'Bayar di Tempat'],
],
```

```html
@foreach(config('sadita.payment_methods') as $method)
<button
    wire:click="selectPayment('{{ $method['key'] }}')"
    class="{{ $selectedPayment === $method['key'] ? 'border-primary bg-primary/5' : 'border-outline-variant' }}"
    aria-pressed="{{ $selectedPayment === $method['key'] ? 'true' : 'false' }}"
>
    <span class="material-symbols-outlined">{{ $method['icon'] }}</span>
    <span>{{ $method['label'] }}</span>
    <span class="text-secondary">{{ $method['sublabel'] }}</span>
</button>
@endforeach
```


### Sticky Bottom Bar

```html
<!-- _sticky-bar.blade.php -->
<div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[512px] z-50
            bg-white/95 backdrop-blur border-t border-outline-variant px-5 py-3
            flex items-center justify-between">
    <div>
        <p class="text-xs text-secondary">Total Pembayaran</p>
        <p class="text-xl font-black text-primary">
            Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
        </p>
    </div>
    <button
        wire:click="submitCheckout"
        wire:loading.attr="disabled"
        wire:target="submitCheckout"
        :disabled="!$wire.selectedPayment || $wire.isSubmitting"
        class="h-12 px-6 rounded-xl bg-primary text-white font-bold text-sm
               disabled:opacity-50 disabled:cursor-not-allowed"
        aria-label="Lanjutkan pembayaran"
    >
        <span wire:loading.remove wire:target="submitCheckout">Lanjutkan</span>
        <span wire:loading wire:target="submitCheckout">Memproses...</span>
    </button>
</div>
```

### Section 4: Voucher (Disabled untuk V3.0)

```php
// config/sadita.php
'voucher_enabled' => env('SADITA_VOUCHER_ENABLED', false),
```

```html
<!-- _section-voucher.blade.php -->
@php $voucherEnabled = config('sadita.voucher_enabled', false); @endphp
<section>
    <h2>Voucher</h2>
    @unless($voucherEnabled)
        <span class="badge">Segera Hadir</span>
    @endunless
    <div class="{{ $voucherEnabled ? '' : 'opacity-50 pointer-events-none' }}">
        <input type="text" wire:model="voucherCode"
               @disabled(!$voucherEnabled)
               placeholder="Masukkan kode voucher">
        <button type="button" @disabled(!$voucherEnabled)>Gunakan</button>
    </div>
</section>
```

---

## Desain Keamanan

### CSRF Protection

Semua form submission Livewire otomatis menggunakan CSRF token melalui middleware Laravel (`VerifyCsrfToken`). Route checkout V3 tidak di-exclude dari middleware ini.

### Rate Limiting

```php
// app/Providers/AppServiceProvider.php (atau RouteServiceProvider)
RateLimiter::for('checkout-submit', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip())
        ->response(function () {
            return response()->json([
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam 60 detik.'
            ], 429);
        });
});

// routes/web.php
Route::post('/checkout-v3/store', [CheckoutV3Controller::class, 'store'])
    ->name('checkout-v3.store')
    ->middleware('throttle:checkout-submit');
```

### Server-Side Validation (CheckoutV3Request)

```php
class CheckoutV3Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:100',
            'phone'         => 'required|digits_between:10,15',
            'province_id'   => 'required|exists:provinces,id',
            'regency_id'    => 'required|exists:regencies,id',
            'district_id'   => 'required|exists:districts,id',
            'village_id'    => 'required|exists:villages,id',
            'address'       => 'required|string|max:255',
            'postal_code'   => 'nullable|digits:5',
            'notes'         => 'nullable|string|max:500',
            'payment_method'=> 'required|in:transfer,qris,cod',
            'shipping_key'  => 'required|string',  // key metode yang dipilih
        ];
    }
}
```

### Price Manipulation Prevention

```php
// Di OrderService::validatePrices()
foreach ($cartItems as $item) {
    $product = Product::find($item['product_id']);
    if ((int) $item['price'] !== (int) $product->price) {
        throw new PriceChangedException(
            "Harga {$product->name} telah berubah. Mohon periksa kembali pesanan Anda.",
            ['product_id' => $product->id, 'correct_price' => $product->price]
        );
    }
}
```

---

## Strategi Caching

### Cache Ongkir

```
Key format:  "shipping_v3:{warehouseId}:{villageCode}:{weightKg}"
TTL:         600 detik (10 menit)
Driver:      cache driver default (file/redis/memcached sesuai .env)
Invalidasi:  TTL-based only (tidak ada manual invalidasi)

Kapan TIDAK dipanggil ulang:
- Perubahan metode pembayaran
- Perubahan kode voucher
- Re-render component (data sudah di state Livewire)

Kapan dipanggil ulang:
- Perubahan villageId
- Klik tombol "Coba Lagi" saat error ongkir
```

### Cache Region (sudah ada)

Data provinsi, kota, kecamatan, kelurahan disimpan di database lokal. Tidak perlu cache tambahan karena query menggunakan index dan data relatif statis.


---

## Error Handling

### Error Categories dan Pesan

| Kondisi | Pesan Pengguna | Aksi |
|---|---|---|
| Cart kosong saat mount | Redirect ke katalog | Redirect |
| Field wajib kosong | "Nama lengkap wajib diisi." | Highlight field |
| WhatsApp tidak valid | "Nomor WhatsApp harus terdiri dari 10–15 digit angka." | Highlight field |
| Metode pembayaran tidak dipilih | "Pilih metode pembayaran terlebih dahulu." | Scroll ke section 5 |
| Ongkir API gagal | "Tidak dapat menghitung ongkir saat ini. Silakan coba lagi." | Tombol "Coba Lagi" |
| Harga berubah | "Harga [Nama Produk] telah berubah. Silakan periksa kembali pesanan Anda." | Enable tombol |
| Stok habis | "Stok [Produk A] dan [Produk B] tidak mencukupi." | Enable tombol |
| Ongkir server berbeda | "Tarif ongkir telah berubah. Silakan pilih ulang metode pengiriman." | Trigger recalc |
| Rate limit | "Terlalu banyak percobaan. Silakan coba lagi dalam 60 detik." | Disable 60 detik |
| Generic server error | "Terjadi kesalahan. Silakan coba lagi beberapa saat." | Enable tombol |

### Error Display Pattern

```html
<!-- Setiap field input diikuti error span -->
@error('name')
    <p role="alert" class="mt-1 text-xs font-medium text-error">{{ $message }}</p>
@enderror

<!-- Error global di atas sticky bar -->
@if($this->hasGlobalError)
    <div role="alert" class="mx-5 mb-3 rounded-xl bg-error-container px-4 py-3 text-sm text-on-error-container">
        {{ $this->globalError }}
    </div>
@endif
```

### Offline State

Menggunakan offline indicator dari `app.blade.php` yang sudah ada. Tambahan di Livewire:

```html
<!-- Alpine.js mendeteksi offline state dan disable tombol -->
<div x-data="{ online: navigator.onLine }"
     @online.window="online = true"
     @offline.window="online = false">
    <button :disabled="!online || $wire.isSubmitting" ...>Lanjutkan</button>
</div>
```

---

## Correctness Properties

*Properti adalah karakteristik atau perilaku yang harus berlaku di semua eksekusi sistem yang valid — pada dasarnya, pernyataan formal tentang apa yang harus dilakukan sistem. Properti berfungsi sebagai jembatan antara spesifikasi yang dapat dibaca manusia dan jaminan kebenaran yang dapat diverifikasi oleh mesin.*

### Property 1: Cascading Dropdown — Kota Sesuai Provinsi

*Untuk setiap* provinsi yang dipilih, semua kota/kabupaten yang dimuat harus memiliki `province_id` yang sama dengan provinsi tersebut; tidak ada kota dari provinsi lain yang boleh muncul.

**Validates: Requirements 2.3**

---

### Property 2: Cascading Dropdown — Kecamatan Sesuai Kota

*Untuk setiap* kota yang dipilih, semua kecamatan yang dimuat harus memiliki `regency_id` yang sama dengan kota tersebut.

**Validates: Requirements 2.4**

---

### Property 3: Cascading Dropdown — Kelurahan Sesuai Kecamatan

*Untuk setiap* kecamatan yang dipilih, semua kelurahan yang dimuat harus memiliki `district_id` yang sama dengan kecamatan tersebut.

**Validates: Requirements 2.5**

---

### Property 4: Validasi Field Wajib Menolak Input Kosong

*Untuk setiap* kombinasi data form di mana satu atau lebih field wajib (nama, telepon, province_id, regency_id, district_id, village_id, address) bernilai kosong atau whitespace-only, validasi server harus gagal dan mengembalikan pesan error yang menyebutkan field yang bermasalah.

**Validates: Requirements 2.7**

---

### Property 5: Validasi WhatsApp Menolak Format Non-Numerik atau Panjang Di Luar Rentang

*Untuk setiap* string yang bukan rangkaian 10–15 digit angka (misalnya mengandung huruf, spasi, tanda baca, atau panjangnya di luar 10–15), validasi harus menolaknya. *Untuk setiap* string yang terdiri dari tepat 10–15 digit angka, validasi harus menerimanya.

**Validates: Requirements 2.8**

---

### Property 6: SADITA Courier Selalu Hadir untuk Makassar/Maros

*Untuk setiap* nama kota yang mengandung "makassar" atau "maros" (case-insensitive), hasil dari `ShippingService::getAvailableShippingMethods()` harus selalu menyertakan setidaknya satu entri dengan `type = 'kurir_sadita'`.

**Validates: Requirements 3.5**

---

### Property 7: Gratis Ongkir SADITA untuk Berat ≥ 10 kg

*Untuk setiap* berat dalam gram yang lebih besar dari atau sama dengan 10.000, dan kota tujuan adalah Makassar atau Maros, entri Kurir SADITA dalam hasil kalkulasi harus memiliki `cost = 0` dan `is_free = true`.

**Validates: Requirements 3.6**

---

### Property 8: Urutan Metode Pengiriman (Murah → ETD → Alfabetis)

*Untuk setiap* daftar metode pengiriman yang dikembalikan oleh `ShippingService`, setelah diurutkan, berlaku: untuk setiap pasangan `methods[i]` dan `methods[j]` di mana `i < j`, maka `cost[i] <= cost[j]`; jika `cost[i] == cost[j]` maka `etd_lower[i] <= etd_lower[j]`; jika keduanya sama maka `name[i] <= name[j]` secara alfabetis.

**Validates: Requirements 3.8**

---

### Property 9: Cache Ongkir — API Hanya Dipanggil Sekali untuk Parameter Sama

*Untuk setiap* kombinasi `(warehouseId, villageCode, weightKg)`, jika `ShippingService` dipanggil dua kali berturut-turut dengan parameter yang sama, `ApiCoIdService::getRates()` hanya boleh dipanggil satu kali; panggilan kedua harus menggunakan data dari cache.

**Validates: Requirements 3.10**

---

### Property 10: Perubahan Metode Pembayaran Tidak Memanggil Ulang API Ongkir

*Untuk setiap* urutan aksi di mana kalkulasi ongkir sudah selesai dan kemudian metode pembayaran diubah, jumlah total panggilan ke `ApiCoIdService::getRates()` tidak boleh bertambah.

**Validates: Requirements 3.11**

---

### Property 11: Total Pembayaran = Subtotal + Ongkir − Diskon

*Untuk setiap* nilai subtotal, ongkir, dan diskon yang valid (bilangan bulat non-negatif, diskon ≤ subtotal + ongkir), nilai grand total yang dihitung harus sama persis dengan `subtotal + shippingCost - discount`.

**Validates: Requirements 4.6, 7.1**

---

### Property 12: Format Rupiah Konsisten untuk Semua Nilai Integer

*Untuk setiap* bilangan bulat non-negatif yang diformat sebagai harga, output harus sesuai pola `"Rp X"` di mana X adalah angka dengan pemisah ribuan titik dan tanpa desimal (contoh: 210000 → "Rp 210.000", 0 → "Rp 0").

**Validates: Requirements 7.3**

---

### Property 13: Baris Diskon Terlihat Iff Nilai Diskon > 0

*Untuk setiap* nilai diskon: jika diskon = 0 atau null, baris diskon harus tersembunyi dari DOM atau tidak terrender; jika diskon > 0, baris diskon harus terlihat dan menampilkan nilai sebagai pengurang.

**Validates: Requirements 7.4**

---

### Property 14: Harga Tersimpan di OrderItems = Harga dari Database (Bukan Frontend)

*Untuk setiap* order yang berhasil dibuat, nilai `price` di setiap `order_items` harus sama dengan nilai `price` dari tabel `products` pada saat transaksi, bukan dari data yang dikirimkan frontend. Jika frontend mengirimkan harga yang berbeda, order harus ditolak.

**Validates: Requirements 9.1, 9.5, 8.6**

---

### Property 15: Validasi Stok — Order Ditolak Jika Stok Kurang

*Untuk setiap* order di mana satu atau lebih produk memiliki jumlah yang dipesan melebihi stok tersedia di warehouse, `OrderService` harus menolak pembuatan order dan response error harus menyebutkan nama semua produk yang stoknya tidak mencukupi.

**Validates: Requirements 9.2, 9.6**

---

### Property 16: Rollback Sempurna — Tidak Ada Data Tersimpan Jika Order Gagal

*Untuk setiap* skenario di mana terjadi kegagalan (exception) pada tahap mana pun dalam proses pembuatan order, tidak boleh ada record baru di tabel `orders`, `order_items`, atau perubahan di tabel `product_stocks` yang tersimpan secara permanen di database.

**Validates: Requirements 8.5, 11.6**

---

### Property 17: Rate Limiting — Request ke-11 Mendapat HTTP 429

*Untuk setiap* IP address yang mengirimkan 10 request submit checkout dalam satu menit, request ke-11 dan seterusnya dalam menit yang sama harus mendapatkan response HTTP 429 dengan pesan yang sesuai.

**Validates: Requirements 9.8**

---

### Property 18: Auto-Prefill Profile — Field Terisi dari Data User yang Login

*Untuk setiap* user yang sudah login dan memiliki data profil (name, phone), saat Livewire component di-mount, field `name` harus terisi dari `user->name` dan field `phone` dari `user->phone`; field lain dibiarkan kosong jika data tidak tersedia.

**Validates: Requirements 2.10**

---

### Property 19: Metode Pembayaran Dirender dari Konfigurasi

*Untuk setiap* jumlah N entri dalam `config('sadita.payment_methods')`, halaman checkout harus merender tepat N card metode pembayaran, masing-masing dengan key, label, dan sub-label yang sesuai entri konfigurasi.

**Validates: Requirements 6.4**


---

## Testing Strategy

### Prinsip Pengujian Ganda

Checkout V3 menggunakan dua lapisan pengujian yang saling melengkapi:

1. **Unit/Property Tests** — memverifikasi logika bisnis secara terisolasi dengan banyak input yang di-generate secara acak.
2. **Integration/Feature Tests** — memverifikasi alur end-to-end dengan database dan service nyata (atau mock minimal).

Hindari duplikasi: jika sebuah behavior sudah dicakup oleh property test, unit test hanya perlu contoh spesifik yang penting (edge case), bukan pengulangan.

### Property-Based Testing

Framework: **[PestPHP](https://pestphp.com/)** dengan plugin **[pest-plugin-drift](https://github.com/pestphp/pest-plugin-drift)** atau **[Hypothesis-style dengan custom generators]**. Untuk PHP, gunakan **[PestPHP dengan dataset generation](https://pestphp.com/docs/datasets)** atau library **[stateful/property-testing](https://github.com/nikic/PHP-Parser)**.

**Rekomendasi konkret:** gunakan **`pest-plugin-arch`** untuk arsitektur dan **custom dataset generator** dengan `Pest::dataset()` untuk property tests. Setiap property test harus dijalankan minimal **100 iterasi** dengan input yang di-generate secara acak.

**Konfigurasi:** setiap property test harus diberi tag komentar:
```
// Feature: checkout-v3, Property N: <teks properti>
```

#### Implementasi Property Tests

**Properti 4 & 5 — Validasi Form:**

```php
// Feature: checkout-v3, Property 4: Validasi field wajib menolak input kosong
it('menolak form dengan field wajib kosong', function (array $data) {
    $response = $this->post('/checkout-v3/store', $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(array_keys(
        array_filter($data, fn($v) => $v === '' || $v === null)
    ));
})->with(function () {
    for ($i = 0; $i < 100; $i++) {
        $required = ['name', 'phone', 'province_id', 'regency_id', 'district_id', 'village_id', 'address'];
        $fieldsToEmpty = fake()->randomElements($required, fake()->numberBetween(1, count($required)));
        $data = validCheckoutData();
        foreach ($fieldsToEmpty as $field) {
            $data[$field] = '';
        }
        yield [$data];
    }
});
```

**Properti 7 — Gratis Ongkir SADITA:**

```php
// Feature: checkout-v3, Property 7: Gratis ongkir untuk berat >= 10kg ke Makassar/Maros
it('ongkir SADITA gratis jika berat >= 10kg ke Makassar atau Maros', function (int $weightGrams, string $city) {
    $service = new ShippingService();
    $methods = $service->getAvailableShippingMethods('any_village', $city, $weightGrams, 1);
    $saditaMethod = collect($methods)->firstWhere('type', 'kurir_sadita');
    expect($saditaMethod)->not->toBeNull();
    expect($saditaMethod['cost'])->toBe(0);
    expect($saditaMethod['is_free'])->toBeTrue();
})->with(function () {
    $cities = ['Makassar', 'Kota Makassar', 'makassar', 'Maros', 'Kabupaten Maros'];
    for ($i = 0; $i < 100; $i++) {
        $weight = fake()->numberBetween(10000, 100000); // 10kg - 100kg
        $city = fake()->randomElement($cities);
        yield [$weight, $city];
    }
});
```

**Properti 8 — Urutan Pengiriman:**

```php
// Feature: checkout-v3, Property 8: Urutan metode pengiriman (murah -> etd -> alfa)
it('metode pengiriman diurutkan dengan benar', function (array $methods) {
    $service = new ShippingService();
    $sorted = $service->sortShippingMethods($methods);
    for ($i = 0; $i < count($sorted) - 1; $i++) {
        $a = $sorted[$i];
        $b = $sorted[$i + 1];
        if ($a['cost'] !== $b['cost']) {
            expect($a['cost'])->toBeLessThanOrEqual($b['cost']);
        } elseif ($a['etd_lower'] !== $b['etd_lower']) {
            expect($a['etd_lower'])->toBeLessThanOrEqual($b['etd_lower']);
        } else {
            expect($a['name'])->toBeLessThanOrEqualTo($b['name']);
        }
    }
})->with(function () {
    for ($i = 0; $i < 100; $i++) {
        $n = fake()->numberBetween(2, 10);
        $methods = [];
        for ($j = 0; $j < $n; $j++) {
            $methods[] = [
                'name' => fake()->word(),
                'cost' => fake()->numberBetween(0, 100000),
                'etd_lower' => fake()->numberBetween(1, 14),
                'type' => 'api_co_id',
            ];
        }
        yield [$methods];
    }
});
```

**Properti 11 — Kalkulasi Total:**

```php
// Feature: checkout-v3, Property 11: Grand total = subtotal + ongkir - diskon
it('grand total selalu = subtotal + ongkir - diskon', function (int $subtotal, int $shipping, int $discount) {
    $component = Livewire::test(CheckoutV3Page::class);
    $expected = $subtotal + $shipping - $discount;
    // inject values directly
    $actual = $component->instance()->calculateGrandTotal($subtotal, $shipping, $discount);
    expect($actual)->toBe($expected);
})->with(function () {
    for ($i = 0; $i < 100; $i++) {
        $subtotal = fake()->numberBetween(10000, 10000000);
        $shipping = fake()->numberBetween(0, 200000);
        $discount = fake()->numberBetween(0, $subtotal);
        yield [$subtotal, $shipping, $discount];
    }
});
```

**Properti 14 — Harga dari Database:**

```php
// Feature: checkout-v3, Property 14: Harga di order_items = harga dari database
it('menolak order jika harga frontend berbeda dari database', function (int $actualPrice, int $manipulatedPrice) {
    assume($actualPrice !== $manipulatedPrice);
    $product = Product::factory()->create(['price' => $actualPrice]);
    CartService::addToSession($product->id, 1, $manipulatedPrice);

    $response = $this->post('/checkout-v3/store', validCheckoutData());
    $response->assertStatus(422);
    $this->assertDatabaseMissing('orders', ['customer_name' => 'Test Customer']);
})->with(function () {
    for ($i = 0; $i < 100; $i++) {
        $actual = fake()->numberBetween(10000, 500000);
        $manipulated = $actual + fake()->randomElement([-5000, -1, 1, 5000, 100000]);
        yield [$actual, $manipulated];
    }
});
```

**Properti 16 — Rollback:**

```php
// Feature: checkout-v3, Property 16: Rollback sempurna jika order gagal
it('tidak ada data tersimpan jika order gagal di tahap manapun', function (string $failurePoint) {
    // Setup: mock failure di titik tertentu
    $this->mock(OrderService::class, function ($mock) use ($failurePoint) {
        $mock->shouldReceive('createOrder')->andThrow(new \Exception("Simulated failure at $failurePoint"));
    });
    $orderCountBefore = Order::count();
    $stockBefore = ProductStock::sum('stock');

    $this->post('/checkout-v3/store', validCheckoutData());

    expect(Order::count())->toBe($orderCountBefore);
    expect(ProductStock::sum('stock'))->toBe($stockBefore);
})->with(function () {
    $points = ['price_validation', 'stock_validation', 'shipping_validation', 'order_creation', 'stock_deduction'];
    for ($i = 0; $i < 100; $i++) {
        yield [fake()->randomElement($points)];
    }
});
```

### Integration Tests

```
tests/Feature/CheckoutV3/
├── CheckoutPageTest.php          -- mount, redirect jika cart kosong, struktur 7 section
├── ShippingCalculationTest.php   -- integrasi ShippingService dengan API mock
├── OrderCreationFlowTest.php     -- alur lengkap order berhasil
├── ValidationTest.php            -- semua skenario validasi
└── RateLimitTest.php             -- verifikasi rate limit 429
```

### Unit Tests

```
tests/Unit/Services/
├── ShippingServiceTest.php       -- isSaditaCity, sortMethods, free shipping logic
├── OrderServiceTest.php          -- calculatePrices, validateStock, generateToken
└── FormatRupiahTest.php          -- fungsi format Rupiah dengan berbagai nilai
```

### Checklist Aksesibilitas

- [ ] Setiap `<input>` memiliki `<label for="...">` dengan atribut `id` yang matching
- [ ] Field wajib ditandai dengan `*` dan `aria-required="true"`
- [ ] Card interaktif menggunakan `<button>` bukan `<div>` untuk keyboard navigation
- [ ] Tombol tanpa teks terlihat memiliki `aria-label`
- [ ] Pesan error memiliki `role="alert"` untuk screen reader
- [ ] Kontras teks memenuhi WCAG 2.1 AA (diverifikasi dengan Lighthouse/Axe)

