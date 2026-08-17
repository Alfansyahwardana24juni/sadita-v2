# Cek Ongkir dan Metode Pengiriman

Dokumen ini menjadi acuan agar fitur cek ongkir dan metode pengiriman tetap konsisten.

## Ringkasan

Checkout menggunakan API.co.id untuk tarif ekspedisi nasional.

- Provider ongkir: API.co.id
- Endpoint tarif: `GET https://use.api.co.id/expedition/shipping-cost`
- Header auth: `x-api-co-id`
- Basis perhitungan: kode kelurahan/desa 10 digit
- Berat dikirim ke API.co.id dalam kilogram, dibulatkan ke atas
- Biteship sudah tidak dipakai

## File Utama

- `app/Services/ApiCoIdService.php`
- `app/Http/Controllers/Api/ShippingController.php`
- `resources/views/toko/checkout.blade.php`
- `config/api_co_id.php`
- `.env`

## Konfigurasi .env

Isi API key dan kode asal gudang di `.env`:

```env
API_CO_ID_API_KEY=isi_api_key_dari_dashboard_api_co_id
API_CO_ID_BASE_URL=https://use.api.co.id

API_CO_ID_ORIGIN_VILLAGE_CODE=7371141001
API_CO_ID_ORIGIN_VILLAGE_CODE_BOGOR_TIMUR=3271021002
API_CO_ID_ORIGIN_VILLAGE_CODE_MAKASSAR=7371141001

API_CO_ID_ITEM_WEIGHT_GRAMS=500
```

Kode asal yang saat ini dipakai:

- `sadita-bogor-timur`: `3271021002` atau Baranangsiang, Bogor Timur
- `sadita-makassar`: `7371141001` atau Tamalanrea, Makassar
- fallback global: `7371141001`

Jika alamat gudang berubah, update kode kelurahan asal di `.env`, bukan di kode aplikasi.

## Alur Checkout

Customer wajib memilih wilayah lengkap:

1. Provinsi
2. Kota/Kabupaten
3. Kecamatan
4. Kelurahan/Desa
5. Kode pos

Kode kelurahan/desa dari pilihan customer dikirim sebagai `destination_village_code`.

Kode kelurahan asal diambil dari slug gudang terpilih:

```php
config('api_co_id.origin_village_codes.' . $warehouse->slug)
```

Jika mapping gudang tidak ditemukan, sistem memakai:

```php
config('api_co_id.origin_village_code')
```

## Validasi Submit Order

Saat customer menekan `Pesan Sekarang`, backend wajib menghitung ulang:

- harga produk dari tabel `products`
- subtotal dari harga produk saat ini dikali quantity
- berat dari total quantity dan `API_CO_ID_ITEM_WEIGHT_GRAMS`
- ongkir dari rule lokal atau API.co.id
- stok tersedia dari `product_stocks.stock - product_stocks.reserved_stock`

Nilai hidden input berikut hanya dipakai untuk mencocokkan pilihan customer, bukan sumber kebenaran:

- `shipping_method`
- `shipping_service`
- `shipping_cost`

Jika metode atau ongkir yang dikirim frontend tidak cocok dengan hasil hitung ulang server, order harus ditolak dan customer diminta memuat ulang ongkir.

## Rule Metode Pengiriman

Rule yang dipakai:

1. Tujuan Makassar atau Maros:
   - tampilkan `Kurir SADITA`
   - tampilkan `Ambil Langsung`
   - gratis ongkir jika berat >= 10 kg

2. Tujuan selain Makassar/Maros:
   - tampilkan ekspedisi nasional dari API.co.id
   - hasil diurutkan dari ongkir termurah
   - courier dengan harga `0`, kode kosong, atau nama `Unknown Courier` tidak ditampilkan
   - jangan fallback ke RajaOngkir di preview atau submit checkout

Jangan ubah rule lokal menjadi berdasarkan kota asal gudang. Kurir SADITA hanya untuk tujuan Makassar/Maros.

## Payload Tarif

Checkout memanggil:

```http
POST /api/shipping/methods
```

Payload penting:

```json
{
  "regency_id": "5103",
  "city_name": "Kabupaten Badung",
  "origin_village_code": "7371141001",
  "destination_village_code": "5103032008",
  "postal_code": "80351",
  "weight": 500,
  "total": 100000
}
```

Controller lalu memanggil API.co.id:

```http
GET /expedition/shipping-cost?origin_village_code=7371141001&destination_village_code=5103032008&weight=1
```

## Format Metode Pengiriman

Semua metode yang dikirim ke frontend harus mengikuti format ini:

```php
[
    'type' => 'api_co_id',
    'courier' => 'JNE',
    'name' => 'JNE Express',
    'service_code' => 'JNE',
    'service_name' => '',
    'cost' => 62000,
    'estimated_days' => '3 - 5 Hari',
    'is_free' => false,
]
```

`service_name` untuk API.co.id sengaja dikosongkan supaya tampilan tidak dobel seperti `JNE Express - JNE Express`.

## Data Wilayah

Endpoint lokal:

- `GET /api/shipping/provinces`
- `GET /api/shipping/cities?province_id=xx`
- `GET /api/shipping/districts?regency_id=xx`
- `GET /api/shipping/villages?district_id=xx`

Jika tabel `districts` atau `villages` kosong, controller akan mengambil data dari Regional API.co.id dan menyimpannya ke database lokal dengan `upsert`.

## Hal Yang Tidak Boleh Diubah Sembarangan

- Jangan mengembalikan Biteship.
- Jangan mengganti API.co.id dengan provider lain tanpa update dokumen ini.
- Jangan menghapus pilihan kecamatan/kelurahan, karena API.co.id butuh kode desa/kelurahan 10 digit.
- Jangan menampilkan `Unknown Courier`.
- Jangan menampilkan nama layanan dobel.
- Jangan menaruh API key di file selain `.env`.

## Checklist Jika Ongkir Bermasalah

1. Pastikan `API_CO_ID_API_KEY` terisi.
2. Pastikan kode asal gudang di `.env` terisi.
3. Jalankan `php artisan optimize:clear`.
4. Pilih ulang wilayah checkout sampai kelurahan/desa.
5. Cek `storage/logs/laravel.log` untuk error `API.co.id shipping cost`.

## Verifikasi Cepat

Contoh test manual via Tinker:

```bash
php artisan tinker --execute '$service = app(App\Services\ApiCoIdService::class); $rates = $service->getRates("5103032008", 500, "7371141001"); echo count($rates).PHP_EOL; echo ($service->getLastError() ?: "OK").PHP_EOL;'
```

Ekspektasi: jumlah tarif lebih dari `0` dan output error `OK`.
