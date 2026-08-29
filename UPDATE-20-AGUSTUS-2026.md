# 🚀 CHANGELOG & UPDATE SUMMARY - SADITA
**Tanggal:** 20 Agustus 2026

Berikut adalah rangkuman lengkap pembaruan sistem SADITA, meliputi penambahan fitur baru, optimasi antarmuka, hingga perbaikan bug (Frontend & Backend).

---

## 🌟 1. Pengembangan Fitur Utama (Frontend & Backend)
- **Manajemen Pesanan Pembeli (Edit & Batal):** 
  - Menambahkan fitur **Request Cancel** beserta alasannya untuk pesanan yang belum dikirim.
  - Menambahkan fitur **Edit Pesanan** yang memungkinkan pembeli mengubah nama penerima, nomor telepon, alamat jalan, dan metode pembayaran (kota tujuan dikunci agar harga ongkir tetap).
- **Navigasi Terpisah (Toko vs Profil Perusahaan):** 
  - Mengubah logika *Bottom Navigation*. Saat pelanggan berada di halaman e-commerce (Toko), menu navigasi tidak akan menampilkan tombol yang melempar mereka ke halaman Profil Perusahaan (seperti Beranda Utama atau Chat). 
  - Menu khusus Toko kini berisi: **Toko, Katalog, Keranjang, Pesanan, dan Bantuan CS WA**.
- **Penyempurnaan AI Assistant (SaditaCare):** 
  - Mengubah Prompt (Instruksi Sistem) AI menjadi berstruktur XML (mirip *Agent Speech*). 
  - Menyelaraskan pengaturan tampilan AI di Admin Panel agar sesuai persis dengan referensi UI yang diminta (Business Info, Tone, Emoji, dll). 
  - Menginstruksikan AI agar merespons layaknya Manusia (CS) dengan maksimum 2 *bubble chat* untuk menghindari format ala robot.
- **Halaman Sukses Checkout:** 
  - Memperbaiki komponen penampil instruksi Transfer Bank dan QRIS agar dinamis menyesuaikan data *Repeater* dari tabel `StoreSetting` (mendukung banyak bank sekaligus).

---


## 📊 2. Transformasi Admin Panel (Filament Dashboard)
- **Lokalisasi Bahasa Indonesia:** Seluruh komponen tombol, tabel, dan peringatan di Admin Panel telah diterjemahkan secara paksa ke Bahasa Indonesia (`APP_LOCALE=id`) agar admin lebih mudah menggunakan sistem.
- **Widget Dashboard Analytics:**
  - **Total Omzet:** Menampilkan pendapatan bulan ini dan indikator perbandingan naik/turun (%) dengan bulan lalu.
  - **Pengunjung Unik:** Mengubah perhitungan trafik berdasarkan *unique user/device session* alih-alih jumlah *refresh* halaman. Tampil untuk data Hari Ini, Minggu Ini, dan Bulan Ini.
  - **Peringatan Stok & Pesanan Baru:** Angka real-time untuk stok menipis dan pesanan pending.
- **Grafik Penjualan (Line Chart):** Visualisasi grafis pendapatan toko selama 7 hari ke belakang.
- **Tabel 5 Pesanan Terbaru:** Ringkasan tabel di halaman utama Admin untuk mempercepat proses konfirmasi order.
- **Sistem Tooltip Informatif:** Menambahkan ikon bantuan "(i)" yang bisa di-*hover* pada semua grafik dan kotak statistik untuk menjelaskan fungsi dan dari mana data tersebut didapat.

---

## 🔍 3. Optimasi SEO & Social Share (Meta Tags)
- **Implementasi OpenGraph & Twitter Card:** Menanamkan script SEO standar sosial media di semua tata letak (*layout*) web.
- **Preview Link Dinamis:** Ketika pelanggan membagikan tautan (link) suatu **Produk** atau **Artikel** ke dalam grup WhatsApp atau Facebook, preview akan secara otomatis menampilkan **Gambar Produk/Artikel, Judul, dan Deskripsi Singkat**, sehingga lebih profesional dan menarik klik.

---

## 🛠️ 4. Perbaikan Bug (Bug Fixes)
- **Fix Fatal Error Filament Action:** Memperbaiki layar putih/error kode 500 (`Class "Filament\Tables\Actions\Action" not found`) saat membuat custom Action di Tabel Pesanan dan Widget Dashboard. Solusi diterapkan dengan pemanggilan namespace `\Filament\Actions\Action` yang tepat sesuai versi aplikasi.
- **Fix Bug Javascript Autocomplete Checkout:** Memperbaiki error pada script di halaman *checkout* yang sebelumnya menyebabkan data metode pengiriman (kurir) dan ongkos kirim kosong saat fitur "Simpan Alamat" dipanggil dari *local storage*.
- **Disclaimer Estimasi Pengiriman:** Menambahkan teks peringatan di form ongkos kirim halaman *checkout* bahwa "Estimasi waktu pengiriman berlaku setelah paket diserahkan oleh SADITA kepada kurir".

---
*Semua perubahan telah disimpan dan di-push ke repository git pada branch `development`.*
