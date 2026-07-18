
# SADITA V3 - Checkout Master Plan

Version: 3.0
Status: Planning
Author: Alfa & ChatGPT
Last Update: 11 Juli 2026

---

# Tujuan

Membangun halaman Checkout yang cepat, sederhana, modern, dan scalable.

Checkout harus memiliki pengalaman seperti:

- Tokopedia
- Shopee
- TikTok Shop

tetapi tetap mempertahankan kebutuhan bisnis SADITA.

---

# Filosofi

Checkout bukan tempat customer berpikir.

Checkout adalah tempat customer menyelesaikan transaksi secepat mungkin.

Setiap elemen yang tidak membantu transaksi harus dihilangkan.

---

# Prinsip UI

Prioritas:

1. Cepat
2. Jelas
3. Sedikit Klik
4. Mobile First
5. Mudah dipahami orang awam

Target waktu checkout:

< 60 detik

---

# Struktur Halaman

Urutan section WAJIB seperti berikut.

────────────────────

1.
Alamat Pengiriman

────────────────────

2.
Metode Pengiriman

────────────────────

3.
Ringkasan Belanja

────────────────────

4.
Voucher (Future)

────────────────────

5.
Metode Pembayaran

────────────────────

6.
Total Pembayaran

────────────────────

7.
Button Checkout

---

# Section 1
Alamat Pengiriman

Field:

✓ Nama

✓ WhatsApp

✓ Provinsi

✓ Kota

✓ Kecamatan

✓ Kelurahan

✓ Alamat

✓ Kode Pos

✓ Catatan

Rule:

Semua dropdown wajib selesai dipilih sebelum ongkir dihitung.

---

# Section 2
Metode Pengiriman

Data berasal dari:

API.co.id

Rule:

Makassar / Maros

↓

Kurir SADITA

↓

Ambil Langsung

↓

Gratis ongkir jika >=10kg

Selain itu

↓

API.co.id

Urutan:

1.
Paling murah

2.
Paling cepat

3.
Layanan lainnya

Jangan langsung menampilkan seluruh layanan.

Default cukup tampilkan layanan terbaik.

Customer dapat membuka:

"Lihat layanan lain"

---

# Loading State

Saat customer memilih Kelurahan

↓

Munculkan

Menghitung Ongkir...

Semua card berubah menjadi Skeleton Loading.

Jangan tampilkan spinner kosong.

---

# Ongkir

Setelah berhasil

tampilkan

Kurir

Estimasi

Harga

Badge

Misal

JNE REG

Rp18.000

2-3 Hari

atau

Gratis

---

# Rule Ongkir

Backend adalah sumber kebenaran.

Frontend hanya menampilkan data.

Backend wajib menghitung ulang:

✓ Harga

✓ Berat

✓ Ongkir

✓ Stock

Jika berbeda

↓

Order ditolak.

---

# Cache Ongkir

Gunakan cache berdasarkan

Origin

Destination

Weight

Warehouse

TTL:

10 menit

Perubahan metode pembayaran

TIDAK BOLEH

memanggil API Ongkir lagi.

---

# Ringkasan Belanja

WAJIB menampilkan

Produk

Subtotal

Total Berat

Gudang

Kurir

Estimasi

Ongkir

Total

Contoh

Produk

Rp190.000

Berat

500 gram

Gudang

Makassar

Kurir

JNE REG

Estimasi

2-3 Hari

Ongkir

Rp20.000

Total

Rp210.000

Semua berubah realtime.

---

# Voucher

Belum dipakai.

Tetapi section tetap dibuat.

Field

Kode Voucher

Button

Gunakan

Backend dapat dikosongkan.

---

# Metode Pembayaran

Jangan menggunakan Select Option.

Gunakan Card.

Contoh

🏦 Transfer Bank

Transfer Manual

────────────

⚡ QRIS

Semua E-Wallet

────────────

💵 COD

Bayar di Tempat

Future Ready

Midtrans

Xendit

Tripay

Tanpa redesign UI.

---

# Total Pembayaran

Tampilkan

Subtotal

Ongkir

Diskon

Total

Total menggunakan ukuran font paling besar.

---

# Tombol Checkout

Jangan gunakan

WhatsApp

sebagai nama tombol.

Gunakan

"Lanjutkan"

atau

"Checkout"

Flow

↓

Buat Order

↓

Generate Invoice

↓

Redirect Pembayaran

↓

WhatsApp (opsional)

---

# UX Rules

Customer tidak boleh bingung.

Customer tidak boleh scroll terlalu panjang.

Customer tidak boleh melihat loading lebih dari 3 detik.

Customer harus selalu mengetahui

berapa total pembayaran.

---

# Backend Rules

Semua validasi dilakukan server.

Frontend tidak boleh dipercaya.

Validasi:

Harga

Stock

Berat

Warehouse

Ongkir

Voucher

Payment

Semua dihitung ulang.

---

# Database Ready

Checkout harus siap mendukung:

✓ Multi Warehouse

✓ Multi Payment Gateway

✓ Voucher

✓ Promo

✓ Cashback

✓ Flash Sale

✓ Free Shipping

✓ Membership

Tanpa redesign database.

---

# Future Features

Roadmap

V3.1

[x] Voucher

V3.2

Midtrans

V3.3

Order Tracking

V3.4

Split Shipment

V3.5

Multi Warehouse

V3.6

Membership

V3.7

Loyalty Point

---

# Checklist UI

[x] Loading Skeleton

[x] Empty State

[x] Error State

[x] Success State

[x] Retry Button

[x] Sticky Summary

[x] Responsive

[x] Accessibility

[x] Dark Mode Ready

---

# Checklist Backend

[x] API.co.id

[x] Cache Ongkir

[x] Recalculate Weight

[x] Recalculate Total

[x] Recalculate Stock

[x] Transaction DB

[x] Rollback

[x] Logging

[x] Exception Handler

[x] Audit Log

---

# Coding Rules

Semua perubahan checkout harus memenuhi prinsip berikut:

- Tidak merusak flow checkout lama.
- Semua rule bisnis tetap dipertahankan.
- Backend menjadi sumber kebenaran (single source of truth).
- UI dibuat modular sehingga setiap section dapat dikembangkan secara terpisah.
- Tidak ada hardcode API key maupun konfigurasi di source code.
- Semua konfigurasi berasal dari file .env atau config.

---

# Success Indicator

Checkout dianggap selesai apabila:

✓ Customer dapat checkout kurang dari 1 menit.

✓ Ongkir akurat.

✓ Tidak ada manipulasi harga.

✓ UI sederhana.

✓ Mudah dipahami pengguna baru.

✓ Siap dikembangkan untuk 5 tahun ke depan.