# Laporan Pembaruan Sistem SADITA
**Tanggal**: 17 Agustus 2026

Berikut adalah laporan perbandingan (Sebelum vs Sesudah) mengenai fitur, perbaikan (*bug fixes*), dan penambahan baru yang telah diimplementasikan ke dalam sistem malam ini:

## 1. Optimalisasi Alur Checkout & Keranjang
| Fitur / Modul | ❌ Sebelum Diperbaiki | ✅ Sesudah Diperbaiki |
| :--- | :--- | :--- |
| **Form Data Pelanggan** | Pelanggan harus selalu mengetik ulang form alamat dan identitas setiap kali melakukan checkout. | Sistem otomatis menyimpan data *(Remember Me)* di peramban, form otomatis terisi pada transaksi berikutnya. |
| **Sistem Keranjang** | Setelah sukses checkout, seluruh barang langsung terhapus dari keranjang, membuat pelanggan sulit membeli ulang barang yang sama. | Barang yang telah dicheckout tetap tersimpan di dalam keranjang *(Cart tidak dikosongkan secara paksa)*. |
| **Form Promo / Voucher** | Form input promo dan voucher tampil di halaman checkout, membuat tampilan panjang dan penuh. | Form promo/voucher dihapus sepenuhnya agar halaman checkout lebih fokus dan sederhana. |

## 2. Penyempurnaan Sistem Ongkos Kirim (Biteship API)
| Fitur / Modul | ❌ Sebelum Diperbaiki | ✅ Sesudah Diperbaiki |
| :--- | :--- | :--- |
| **Pilihan Kota & Kecamatan** | Terdapat kendala (*bug*) di mana setelah memilih "Provinsi", daftar "Kota/Kabupaten" dan "Kelurahan" tidak bisa diklik. | Form wilayah berjalan lancar secara bertahap *(Cascading Dropdown)* dari Provinsi hingga Desa. |
| **Tampilan Ongkos Kirim** | Panel Ongkos Kirim tersembunyi hingga wilayah dipilih, membuat susunan layout (*UI*) kurang konsisten. | Terdapat efek *Loader* (animasi menghitung) yang responsif dan akan muncul tarif secara otomatis begitu wilayah selesai diisi. |

## 3. Navigasi & Pelacakan Pesanan
| Fitur / Modul | ❌ Sebelum Diperbaiki | ✅ Sesudah Diperbaiki |
| :--- | :--- | :--- |
| **Akses Riwayat (Track Order)** | Tidak ada jalan pintas (shortcut) yang cepat menuju halaman lacak pesanan dan riwayat belanja. | Ikon keranjang dan *Track Order* selalu terlihat (*sticky*) di setiap atas halaman *(Home, Katalog, Detail Produk)*. |

## 4. Menu "Pengaturan Umum" di Panel Admin (Baru 🌟)
| Fitur / Modul | ❌ Sebelum Diperbaiki | ✅ Sesudah Diperbaiki |
| :--- | :--- | :--- |
| **Pengaturan Kontak CS** | Nomor WhatsApp Customer Service terpatri mati (*hardcoded*) di dalam file sistem (`config`). Jika mau diubah harus bongkar *coding*. | Terdapat menu khusus **"Pengaturan Umum"** di panel admin untuk mengganti nomor HP kapan saja secara mandiri. |
| **Rekening Bank & QRIS** | Nama Bank, Nomor Rekening, dan QRIS (*Payment Methods*) juga tertulis kaku di dalam kode sistem dan hanya mendukung 1 bank & 1 metode. | Anda bisa menambah, mengubah, dan menghapus metode pembayaran secara **Unlimited** lewat Panel Admin, dan sistem checkout otomatis mengikuti daftar pembayaran yang telah Anda buat. |
