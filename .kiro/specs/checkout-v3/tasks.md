# Implementation Plan: Checkout V3

## Overview

Implementasi lengkap halaman Checkout V3 untuk SADITA menggunakan **Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS**. Fitur ini menggantikan checkout lama dengan arsitektur yang lebih modular, aman, dan siap dikembangkan untuk roadmap V3.1–V3.7.

**Target:** Checkout selesai < 60 detik, ongkir akurat, tidak ada manipulasi harga dari client, UI mobile-first dengan lebar maksimum 512px.

**Route baru:** `GET /checkout-v3` dan `POST /checkout-v3/store` — berjalan paralel dengan route lama.

---

## Tasks

- [ ] 1. Setup Database dan Konfigurasi Awal
  - [ ] 1.1 Buat migration untuk kolom checkout V3 di tabel orders
    - Tambahkan kolom: `shipping_courier`, `shipping_service`, `shipping_etd`, `discount_amount`, `voucher_code`, `cashback_amount`, `customer_postal_code`
    - Gunakan file migration: `database/migrations/2026_07_XX_000001_add_checkout_v3_fields_to_orders_table.php`
    - _Requirements: 12.2, 12.3, 12.4_

  - [ ] 1.2 Update model Order dengan fillable dan relasi baru
    - Tambahkan fillable untuk semua kolom V3 baru
    - Pastikan relasi ke `warehouses`, `provinces`, `regencies`, `districts`, `villages` sudah ada
    - File: `app/Models/Order.php`
    - _Requirements: 12.1, 12.2, 12.3_

  - [ ] 1.3 Buat konfigurasi checkout V3 di config/sadita.php
    - Array payment_methods dengan key, icon, label, sublabel
    - Flag `voucher_enabled` (default false)
    - Konfigurasi SADITA courier (cities, free_weight_threshold, base_cost)
    - _Requirements: 6.2, 6.4, 5.2, 3.5, 3.6_

  - [ ] 1.4 Jalankan migration dan verifikasi struktur database
    - Jalankan `php artisan migrate`
    - Verifikasi kolom baru di tabel orders
    - _Requirements: 12.1, 12.2, 12.3, 12.4_


- [ ] 2. Implementasi Service Layer
  - [ ] 2.1 Upgrade ShippingService untuk checkout V3
    - Tambahkan method `getAvailableShippingMethods(villageCode, regencyName, weightGrams, warehouseId)`
    - Implementasi logika `isSaditaCity(regencyName)` — deteksi Makassar/Maros
    - Implementasi `getLocalRates()` untuk Kurir SADITA (gratis jika ≥10kg) dan Ambil Langsung
    - Implementasi `getApiCoIdRates()` dengan caching (TTL 10 menit)
    - Implementasi `sortShippingMethods()` — urutan: murah → etd → alfabetis
    - File: `app/Services/ShippingService.php`
    - _Requirements: 3.1, 3.4, 3.5, 3.6, 3.7, 3.8, 3.10_

  - [ ]* 2.2 Write property test untuk ShippingService
    - **Property 6: SADITA Courier Selalu Hadir untuk Makassar/Maros**
    - **Validates: Requirements 3.5**

  - [ ]* 2.3 Write property test untuk gratis ongkir SADITA
    - **Property 7: Gratis Ongkir SADITA untuk Berat ≥ 10 kg**
    - **Validates: Requirements 3.6**

  - [ ]* 2.4 Write property test untuk urutan metode pengiriman
    - **Property 8: Urutan Metode Pengiriman (Murah → ETD → Alfabetis)**
    - **Validates: Requirements 3.8**

  - [ ]* 2.5 Write property test untuk caching ongkir
    - **Property 9: Cache Ongkir — API Hanya Dipanggil Sekali untuk Parameter Sama**
    - **Validates: Requirements 3.10**

  - [ ] 2.6 Buat OrderService untuk create order dengan validasi server
    - Implementasi method `createOrder(array $data, array $cartItems)`
    - Implementasi `validatePrices()` — cek harga produk vs database
    - Implementasi `validateStock()` — verifikasi stok tersedia
    - Implementasi `recalculateShipping()` — kalkulasi ulang ongkir di server
    - Implementasi `createOrderRecord()`, `createOrderItems()`, `deductStock()`, `generateSuccessToken()`
    - Semua operasi dalam DB::transaction dengan rollback otomatis jika error
    - File: `app/Services/OrderService.php`
    - _Requirements: 8.3, 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 11.6_

  - [ ]* 2.7 Write unit test untuk OrderService validasi harga
    - Test case: harga berbeda → exception dengan daftar produk yang benar
    - Test case: harga sama → validasi lulus
    - _Requirements: 9.1, 9.5_

  - [ ]* 2.8 Write unit test untuk OrderService validasi stok
    - Test case: stok tidak cukup → exception dengan daftar produk
    - Test case: stok cukup → validasi lulus
    - _Requirements: 9.2, 9.6_


- [ ] 3. Implementasi Form Request Validation
  - [ ] 3.1 Buat CheckoutV3Request untuk validasi server-side
    - Rules: name (required, max:100), phone (required, digits_between:10,15)
    - Rules: province_id, regency_id, district_id, village_id (required, exists)
    - Rules: address (required, max:255), postal_code (nullable, digits:5), notes (nullable, max:500)
    - Rules: payment_method (required, in:transfer,qris,cod), shipping_key (required, string)
    - Custom messages dalam bahasa Indonesia
    - File: `app/Http/Requests/CheckoutV3Request.php`
    - _Requirements: 2.7, 2.8, 6.5, 9.7_

  - [ ]* 3.2 Write property test untuk validasi field wajib
    - **Property 4: Validasi Field Wajib Menolak Input Kosong**
    - **Validates: Requirements 2.7**

  - [ ]* 3.3 Write property test untuk validasi WhatsApp
    - **Property 5: Validasi WhatsApp Menolak Format Non-Numerik atau Panjang Di Luar Rentang**
    - **Validates: Requirements 2.8**

- [ ] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.


- [ ] 5. Implementasi Livewire Component CheckoutV3Page
  - [ ] 5.1 Buat class CheckoutV3Page dengan semua reactive properties
    - Properties: name, phone, address, postalCode, notes
    - Properties: provinceId, regencyId, districtId, villageId
    - Properties: provinces[], regencies[], districts[], villages[]
    - Properties: shippingMethods[], selectedShipping, isCalculatingShipping
    - Properties: selectedPayment, voucherCode, isSubmitting, errors[]
    - Computed properties: subtotal, totalWeight, shippingCost, discount, grandTotal
    - Constructor injection: ShippingService, CartService
    - File: `app/Livewire/CheckoutV3Page.php`
    - _Requirements: 1.1, 2.1, 3.1, 4.1, 6.1, 7.1, 11.1, 11.3_

  - [ ] 5.2 Implementasi lifecycle mount() untuk load provinces dan prefill user data
    - Load provinces dari database
    - Prefill name dan phone dari user profile jika login
    - Validasi cart tidak kosong, redirect ke katalog jika kosong
    - _Requirements: 1.3, 2.2, 2.10_

  - [ ] 5.3 Implementasi cascading dropdown updaters
    - Method `updatedProvinceId()` — load regencies, reset cascading
    - Method `updatedRegencyId()` — load districts, reset cascading
    - Method `updatedDistrictId()` — load villages, reset cascading
    - Method `updatedVillageId()` — trigger calculateShipping()
    - _Requirements: 2.3, 2.4, 2.5, 2.6_

  - [ ]* 5.4 Write property test untuk cascading dropdown
    - **Property 1: Cascading Dropdown — Kota Sesuai Provinsi**
    - **Property 2: Cascading Dropdown — Kecamatan Sesuai Kota**
    - **Property 3: Cascading Dropdown — Kelurahan Sesuai Kecamatan**
    - **Validates: Requirements 2.3, 2.4, 2.5**

  - [ ] 5.5 Implementasi method calculateShipping()
    - Set isCalculatingShipping = true, tampilkan skeleton loading
    - Panggil ShippingService::getAvailableShippingMethods()
    - Handle error: tampilkan pesan error dan tombol "Coba Lagi"
    - Set shippingMethods, isCalculatingShipping = false
    - _Requirements: 3.1, 3.2, 3.3, 3.12_

  - [ ] 5.6 Implementasi method selectShipping() dan selectPayment()
    - `selectShipping($key)` — set selectedShipping, update shippingCost
    - `selectPayment($method)` — set selectedPayment
    - Update UI dalam < 300ms tanpa reload
    - _Requirements: 4.7, 6.3, 10.3_

  - [ ] 5.7 Implementasi method submitCheckout()
    - Validasi form client-side
    - Set isSubmitting = true, disable tombol
    - Kirim POST request ke CheckoutV3Controller::store()
    - Handle error: tampilkan pesan, enable tombol
    - _Requirements: 8.1, 8.2, 8.4, 8.5, 8.6_

  - [ ]* 5.8 Write property test untuk perubahan payment tidak call API ongkir
    - **Property 10: Perubahan Metode Pembayaran Tidak Memanggil Ulang API Ongkir**
    - **Validates: Requirements 3.11**


- [ ] 6. Implementasi Blade Templates — Root dan Partials
  - [ ] 6.1 Buat template root checkout-v3-page.blade.php
    - Layout mobile-first dengan max-width 512px
    - Include semua partial sections (1-7)
    - Include sticky bottom bar
    - Include skeleton loading partial
    - File: `resources/views/livewire/checkout-v3/checkout-v3-page.blade.php`
    - _Requirements: 1.2, 1.4, 11.1_

  - [ ] 6.2 Buat partial _section-address.blade.php
    - Form grid 2 kolom untuk Nama + WhatsApp
    - Dropdown cascading untuk Provinsi, Kota, Kecamatan, Kelurahan
    - Field Alamat, Kode Pos, Catatan
    - Label dengan asterisk untuk field wajib
    - Error message di bawah setiap field
    - Attribut `for`/`id` untuk aksesibilitas
    - File: `resources/views/livewire/checkout-v3/partials/_section-address.blade.php`
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.7, 2.8, 13.1, 13.2, 13.3, 13.4_

  - [ ] 6.3 Buat partial _section-shipping.blade.php
    - Skeleton loading saat isCalculatingShipping = true
    - Card metode pengiriman dengan nama kurir, layanan, etd, harga
    - Badge "Gratis" untuk ongkir Rp 0
    - Default tampilkan satu terbaik, link "Lihat layanan lain"
    - Error state dengan tombol "Coba Lagi"
    - File: `resources/views/livewire/checkout-v3/partials/_section-shipping.blade.php`
    - _Requirements: 3.1, 3.2, 3.4, 3.9, 3.12, 10.1_

  - [ ] 6.4 Buat partial _section-summary.blade.php
    - Daftar produk dengan nama, qty, subtotal per item
    - Tampilkan Total Berat, Gudang
    - Tampilkan Kurir, Layanan, Estimasi (placeholder "—" jika belum pilih)
    - Tampilkan Ongkir (placeholder "—" jika belum pilih)
    - Tampilkan Total Pembayaran
    - File: `resources/views/livewire/checkout-v3/partials/_section-summary.blade.php`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7_

  - [ ] 6.5 Buat partial _section-voucher.blade.php
    - Section dengan badge "Segera Hadir"
    - Field input dan tombol dalam kondisi disabled
    - Render dari config `voucher_enabled`
    - File: `resources/views/livewire/checkout-v3/partials/_section-voucher.blade.php`
    - _Requirements: 5.1, 5.2, 5.3_

  - [ ] 6.6 Buat partial _section-payment.blade.php
    - Render card metode pembayaran dari config array
    - Card dengan icon, label, sublabel
    - Visual feedback border primary untuk card terpilih
    - No default selection saat mount
    - Error message jika tidak dipilih saat submit
    - File: `resources/views/livewire/checkout-v3/partials/_section-payment.blade.php`
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

  - [ ] 6.7 Buat partial _section-total.blade.php
    - Rincian: Subtotal, Ongkir, Diskon (conditional), Total Akhir
    - Total Akhir font size lebih besar
    - Format Rupiah dengan pemisah ribuan titik
    - Baris Diskon hanya tampil jika discount > 0
    - File: `resources/views/livewire/checkout-v3/partials/_section-total.blade.php`
    - _Requirements: 7.1, 7.2, 7.3, 7.4_

  - [ ] 6.8 Buat partial _sticky-bar.blade.php
    - Fixed bottom dengan max-width 512px
    - Tampilkan Total Pembayaran dalam font besar
    - Tombol "Lanjutkan" dengan loading state
    - Disable tombol jika payment belum dipilih atau isSubmitting
    - Alpine.js untuk offline detection
    - File: `resources/views/livewire/checkout-v3/partials/_sticky-bar.blade.php`
    - _Requirements: 1.4, 7.1, 8.1, 8.2, 10.4, 13.5_

  - [ ] 6.9 Buat partial _skeleton-shipping.blade.php
    - Skeleton loading card berbentuk card pengiriman
    - Minimal satu card, bukan spinner kosong
    - File: `resources/views/livewire/checkout-v3/partials/_skeleton-shipping.blade.php`
    - _Requirements: 3.1, 10.1_


- [ ] 7. Checkpoint - Ensure all templates render correctly
  - Ensure all templates render correctly, ask the user if questions arise.

- [ ] 8. Implementasi Controller dan Routes
  - [ ] 8.1 Buat CheckoutV3Controller dengan method index() dan store()
    - `index()` — load checkout page (redirect jika cart kosong)
    - `store()` — terima CheckoutV3Request, panggil OrderService, redirect ke success
    - Wrap createOrder dalam DB::transaction
    - Handle exception: rollback otomatis, return error response
    - File: `app/Http/Controllers/CheckoutV3Controller.php`
    - _Requirements: 8.3, 8.4, 8.5, 8.6, 9.4, 11.5, 11.6_

  - [ ] 8.2 Tambahkan routes checkout V3 di web.php
    - GET `/checkout-v3` → CheckoutV3Controller@index
    - POST `/checkout-v3/store` → CheckoutV3Controller@store (middleware: throttle:checkout-submit)
    - Middleware: auth (optional, guest checkout diizinkan), web, csrf
    - File: `routes/web.php`
    - _Requirements: 11.5_

  - [ ] 8.3 Setup rate limiter untuk checkout submission
    - Limiter name: `checkout-submit`
    - 10 requests per minute per IP
    - Response 429 dengan pesan bahasa Indonesia
    - File: `app/Providers/AppServiceProvider.php` atau `RouteServiceProvider`
    - _Requirements: 9.8_

  - [ ]* 8.4 Write integration test untuk flow checkout end-to-end
    - Test case: user submit checkout valid → order dibuat, stok berkurang, redirect success
    - Test case: user submit dengan harga manipulasi → 422 error, order tidak dibuat
    - Test case: user submit dengan stok habis → 422 error, order tidak dibuat
    - _Requirements: 8.3, 9.1, 9.2, 9.5, 9.6_


- [ ] 9. Implementasi Property-Based Tests untuk Correctness Properties
  - [ ]* 9.1 Write property test untuk total pembayaran formula
    - **Property 11: Total Pembayaran = Subtotal + Ongkir − Diskon**
    - **Validates: Requirements 4.6, 7.1**

  - [ ]* 9.2 Write property test untuk format Rupiah
    - **Property 12: Format Rupiah Konsisten untuk Semua Nilai Integer**
    - **Validates: Requirements 7.3**

  - [ ]* 9.3 Write property test untuk visibility baris diskon
    - **Property 13: Baris Diskon Terlihat Iff Nilai Diskon > 0**
    - **Validates: Requirements 7.4**

- [ ] 10. Implementasi Styling dan Aksesibilitas
  - [ ] 10.1 Styling semua sections menggunakan Tailwind CSS
    - Gunakan design system SADITA (warna primary, secondary, error, outline-variant)
    - Mobile-first dengan max-width 512px
    - Spacing dan typography konsisten dengan layout app
    - _Requirements: 1.2, 1.5_

  - [ ] 10.2 Implementasi loading states untuk semua interaksi async
    - wire:loading untuk calculateShipping, submitCheckout
    - wire:target untuk specificity
    - Skeleton loading untuk shipping methods
    - Button disabled state dengan opacity
    - _Requirements: 3.1, 10.1, 10.5_

  - [ ] 10.3 Implementasi error states dan pesan user-friendly
    - Error message bahasa Indonesia tanpa kode teknis
    - Auto-scroll ke error pertama saat validasi gagal
    - Retain data yang sudah diisi customer
    - Tombol "Coba Lagi" untuk error kalkulasi ongkir
    - _Requirements: 2.9, 3.12, 8.5, 13.3, 13.4_

  - [ ] 10.4 Implementasi aksesibilitas WCAG 2.1 Level AA
    - Rasio kontras teks minimal 4.5:1 (3:1 untuk teks besar)
    - Label terhubung ke input via for/id
    - aria-label untuk elemen interaktif tanpa teks
    - role="alert" untuk error messages
    - aria-pressed untuk card pilihan
    - _Requirements: 13.1, 13.5, 13.6_

  - [ ]* 10.5 Write manual accessibility test dengan screen reader
    - Test navigasi keyboard melalui semua sections
    - Test screen reader announcements untuk error messages
    - Test focus order yang logis
    - _Requirements: 13.1, 13.5, 13.6_


- [ ] 11. Integrasi dan Wiring Akhir
  - [ ] 11.1 Wire OrderService dengan CheckoutV3Controller
    - Inject OrderService ke controller via dependency injection
    - Pass validated data dari CheckoutV3Request ke OrderService::createOrder()
    - Handle exception dan return response yang sesuai
    - _Requirements: 8.3, 8.5, 11.2_

  - [ ] 11.2 Wire ShippingService dengan CheckoutV3Page
    - Inject ShippingService ke Livewire component via constructor
    - Call getAvailableShippingMethods() dari calculateShipping()
    - Handle cache dan error dari service
    - _Requirements: 3.10, 11.3_

  - [ ] 11.3 Setup environment variables untuk konfigurasi
    - SADITA_VOUCHER_ENABLED (default: false)
    - SADITA_COURIER_BASE_COST (default: 50000)
    - SADITA_COURIER_FREE_WEIGHT_THRESHOLD (default: 10000)
    - API_CO_ID_KEY, API_CO_ID_TIMEOUT
    - Update .env.example dengan dokumentasi
    - _Requirements: 11.4_

  - [ ] 11.4 Verifikasi route checkout lama dan V3 berjalan paralel
    - Test akses `/checkout` (route lama) masih berfungsi
    - Test akses `/checkout-v3` (route baru) berfungsi
    - Verifikasi tidak ada konflik route atau middleware
    - _Requirements: 11.5_

  - [ ]* 11.5 Write integration test untuk full checkout flow dengan API.co.id mock
    - Mock ApiCoIdService::getRates()
    - Test flow lengkap: select address → calc shipping → select payment → submit → order created
    - Verifikasi order record, order items, dan stok berkurang
    - _Requirements: 3.7, 8.3, 9.1, 9.2, 9.3, 9.4_

- [ ] 12. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.


- [ ] 13. Manual Testing dan Verification
  - [ ] 13.1 Test flow checkout end-to-end di browser (desktop + mobile)
    - Test semua field input dan validasi
    - Test cascading dropdown untuk seluruh wilayah Indonesia (sample)
    - Test kalkulasi ongkir untuk Makassar/Maros (gratis ongkir ≥10kg)
    - Test kalkulasi ongkir untuk luar kota (API.co.id)
    - Test pemilihan metode pengiriman dan pembayaran
    - Test submit checkout dan redirect ke success page
    - _Requirements: 1.1, 2.1-2.10, 3.1-3.12, 4.1-4.7, 6.1-6.5, 7.1-7.4, 8.1-8.6_

  - [ ] 13.2 Test error handling dan edge cases
    - Test submit dengan cart kosong → redirect katalog
    - Test submit tanpa pilih payment → error message
    - Test submit dengan harga produk berubah → error dengan pesan
    - Test submit dengan stok habis → error dengan daftar produk
    - Test kalkulasi ongkir gagal → error dengan tombol "Coba Lagi"
    - Test rate limit → 429 setelah 10 request/menit
    - _Requirements: 1.3, 2.7, 2.8, 2.9, 3.12, 6.5, 8.5, 8.6, 9.5, 9.6, 9.8_

  - [ ] 13.3 Test performa dan loading states
    - Verifikasi kalkulasi ongkir < 3 detik (koneksi stabil)
    - Verifikasi interaksi lokal < 300ms (dropdown, select payment)
    - Verifikasi skeleton loading muncul saat calc shipping
    - Verifikasi button loading state saat submit
    - Verifikasi offline indicator dan disable tombol saat offline
    - _Requirements: 3.3, 10.1, 10.2, 10.3, 10.4, 10.5_

  - [ ] 13.4 Test UI/UX dan aksesibilitas
    - Verifikasi layout mobile-first max-width 512px
    - Verifikasi sticky bottom bar selalu terlihat
    - Verifikasi warna dan tipografi dari design system SADITA
    - Verifikasi label terhubung ke input (click label → focus input)
    - Verifikasi error message bahasa Indonesia tanpa kode teknis
    - Verifikasi auto-scroll ke error pertama
    - Verifikasi rasio kontras memenuhi WCAG 2.1 AA
    - _Requirements: 1.2, 1.4, 1.5, 13.1, 13.2, 13.3, 13.4, 13.5, 13.6_

  - [ ] 13.5 Test voucher section (future-ready)
    - Verifikasi section voucher disabled dengan badge "Segera Hadir"
    - Verifikasi field dan tombol tidak dapat diklik
    - _Requirements: 5.1, 5.2, 5.3_

  - [ ] 13.6 Test compatibility dengan checkout lama
    - Verifikasi route `/checkout` masih berfungsi
    - Verifikasi tidak ada konflik session atau database
    - Verifikasi order dari checkout V3 dapat dilihat di admin panel
    - _Requirements: 11.5_


---

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties dari design.md
- Unit tests dan integration tests validate specific examples dan edge cases
- Manual testing tasks (13.1-13.6) adalah **coding-adjacent tasks** yang dilakukan developer di browser/tool, bukan user acceptance testing
- Database migration harus dijalankan sebelum implementasi service dan controller
- Service layer harus selesai sebelum Livewire component
- Blade templates dapat dikerjakan paralel dengan Livewire component logic
- Controller dan routes adalah final integration step
- Stack: **PHP 8.2+ / Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS**

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.3"] },
    { "id": 1, "tasks": ["1.2", "1.4", "3.1"] },
    { "id": 2, "tasks": ["2.1", "3.2", "3.3"] },
    { "id": 3, "tasks": ["2.2", "2.3", "2.4", "2.5", "2.6"] },
    { "id": 4, "tasks": ["2.7", "2.8", "5.1"] },
    { "id": 5, "tasks": ["5.2", "5.3", "6.1"] },
    { "id": 6, "tasks": ["5.4", "5.5", "6.2", "6.3", "6.4", "6.5", "6.6", "6.7", "6.9"] },
    { "id": 7, "tasks": ["5.6", "6.8"] },
    { "id": 8, "tasks": ["5.7", "5.8", "8.3"] },
    { "id": 9, "tasks": ["8.1", "8.2"] },
    { "id": 10, "tasks": ["8.4", "9.1", "9.2", "9.3", "10.1"] },
    { "id": 11, "tasks": ["10.2", "10.3", "10.4"] },
    { "id": 12, "tasks": ["10.5", "11.1", "11.2", "11.3"] },
    { "id": 13, "tasks": ["11.4", "11.5"] },
    { "id": 14, "tasks": ["13.1", "13.2", "13.3", "13.4", "13.5", "13.6"] }
  ]
}
```
