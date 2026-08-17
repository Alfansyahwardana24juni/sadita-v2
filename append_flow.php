<?php

$flow = <<<EOT

## 7. Alur Website (Website Flow)

Berikut adalah ringkasan alur penggunaan sistem SADITA secara keseluruhan:

### A. Sisi Pengguna (Frontend / Public)
1. **Eksplorasi Profil & Produk:**
   - User mengunjungi **Beranda** untuk melihat informasi singkat perusahaan.
   - User dapat membuka **Tentang Kami** untuk melihat profil lengkap, fasilitas, sertifikat, dan legalitas.
   - User mengeksplorasi katalog di halaman **Produk** publik.
2. **Konsultasi Kesehatan Hewan:**
   - User yang memiliki pertanyaan medis hewan dapat masuk ke **SaditaCare**.
   - User berinteraksi dengan AI cerdas untuk mendapatkan rekomendasi.
   - *Error Handling:* Jika sistem AI sedang sibuk/habis kuota, sistem otomatis menampilkan peringatan dan mengarahkan user untuk menghubungi via WhatsApp.
3. **Pembelanjaan (Toko SADITA):**
   - User masuk ke **Toko** dan memasukkan produk ke dalam **Keranjang**.
   - Di keranjang, user dapat menambah/mengurangi jumlah produk atau menghapusnya (dengan konfirmasi visual *SweetAlert*).
4. **Checkout & Pengiriman:**
   - User menekan tombol checkout dan diarahkan ke halaman **Checkout**.
   - User mengisi alamat lengkap (Provinsi -> Kota -> Kecamatan -> Kelurahan) yang bersifat berjenjang (*cascading*).
   - Sistem memanggil API RajaOngkir untuk menghitung opsi ongkos kirim.
   - User memilih metode pembayaran (Transfer Bank, QRIS, atau COD). Setiap metode menampilkan instruksi khusus secara dinamis.
   - User melakukan **Buat Pesanan** (dengan konfirmasi dialog).
5. **Pasca Pembelian:**
   - User diarahkan ke halaman **Sukses**, berisi panduan pembayaran dan tombol "Konfirmasi ke Admin (WhatsApp)".
   - User dapat melihat status pemrosesan paket mereka di halaman **Lacak Pesanan** dengan memasukkan ID Pesanan dan Nomor HP.
   - Riwayat belanja yang tersimpan di *browser* dapat diakses lewat menu **Order Saya**.

### B. Sisi Admin (Backend / Filament)
1. **Manajemen Utama (Dashboard):**
   - Admin login melalui `/admin`.
   - Melihat metrik utama seperti total pesanan, produk aktif, dan log chat konsultasi.
2. **Pemrosesan Pesanan:**
   - Saat user menyelesaikan *checkout*, order baru akan muncul di menu **Orders**.
   - Admin mengecek mutasi bank/pembayaran.
   - Admin memperbarui status pesanan: `Pending` -> `Processing` -> `Shipped` (menginput resi) -> `Delivered`.
   - Perubahan status ini akan tercermin seketika saat user memeriksa halaman **Lacak Pesanan**.
3. **Manajemen Konten & Produk:**
   - Admin menambah atau memperbarui data Produk (Stok, Harga, Kategori, Foto).
   - Perubahan akan langsung tampil *real-time* di halaman Toko publik.
   - Admin dapat melihat *Log Konsultasi AI* untuk memahami apa saja kendala penyakit hewan yang sering ditanyakan oleh pelanggan.
EOT;

file_put_contents('dokumentasi.md', $flow, FILE_APPEND);
echo "Alur website berhasil ditambahkan ke dokumentasi.md";
