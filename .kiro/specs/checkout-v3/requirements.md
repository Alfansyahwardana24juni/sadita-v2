# Requirements Document

## Introduction

Fitur **Checkout V3** adalah pembaruan menyeluruh halaman checkout SADITA untuk memberikan pengalaman transaksi yang cepat, sederhana, dan modern — setara dengan platform e-commerce terkemuka seperti Tokopedia, Shopee, dan TikTok Shop, namun tetap mempertahankan seluruh kebutuhan bisnis SADITA.

Filosofi utama: *Checkout bukan tempat customer berpikir. Checkout adalah tempat customer menyelesaikan transaksi secepat mungkin.*

Target waktu checkout: **< 60 detik** dari buka halaman hingga order berhasil dibuat.

Stack teknologi: **Laravel + Livewire + Alpine.js + Tailwind CSS**

---

## Glossary

- **Checkout_Page**: Halaman checkout V3 yang dibangun menggunakan Livewire component
- **Checkout_Controller**: Backend Laravel yang menangani pembuatan order dan validasi akhir
- **Shipping_Service**: Service layer yang berkomunikasi dengan API.co.id untuk kalkulasi ongkir
- **Order_Service**: Service layer yang bertanggung jawab atas pembuatan order, kalkulasi ulang harga, dan manajemen stok
- **Cart**: Data keranjang belanja yang disimpan di session sebelum checkout
- **Order**: Entitas pesanan yang telah berhasil dibuat di database
- **OrderItem**: Item individual dalam sebuah Order
- **Warehouse**: Entitas gudang asal pengiriman (Makassar, Maros, dll.)
- **ShippingMethod**: Metode pengiriman yang dipilih customer (kurir + layanan)
- **PaymentMethod**: Metode pembayaran yang dipilih customer (Transfer Bank, QRIS, COD)
- **ShippingCost**: Biaya ongkos kirim yang dihitung berdasarkan origin, destination, berat, dan gudang
- **Region_API**: Data wilayah Indonesia (Provinsi, Kota, Kecamatan, Kelurahan) dari database lokal
- **Ongkir_API**: API.co.id yang digunakan untuk kalkulasi ongkir pengiriman reguler
- **SADITA_Courier**: Kurir internal SADITA yang tersedia khusus untuk area Makassar dan Maros
- **Skeleton_Loading**: Placeholder animasi yang ditampilkan selama data sedang dimuat
- **Invoice**: Dokumen tagihan yang di-generate setelah order berhasil dibuat
- **Success_Token**: Token unik untuk mengakses halaman konfirmasi pembayaran
- **Voucher**: Kode diskon yang akan diimplementasikan pada versi mendatang (V3.1)

---

## Requirements

### Requirement 1: Struktur dan Navigasi Halaman Checkout

**User Story:** Sebagai customer, saya ingin melihat halaman checkout yang terstruktur dan mudah dipahami, sehingga saya dapat menyelesaikan transaksi dengan cepat tanpa kebingungan.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan tujuh section dari atas ke bawah secara berurutan: (1) Alamat Pengiriman, (2) Metode Pengiriman, (3) Ringkasan Belanja, (4) Voucher, (5) Metode Pembayaran, (6) Total Pembayaran, (7) Tombol Checkout.
2. THE Checkout_Page SHALL menggunakan desain mobile-first dengan lebar maksimum 512px mengikuti layout aplikasi SADITA yang sudah ada.
3. WHEN customer membuka halaman checkout dengan Cart kosong, THE Checkout_Page SHALL mengarahkan customer ke halaman katalog produk.
4. THE Checkout_Page SHALL menampilkan sticky bottom bar yang selalu terlihat di viewport tanpa scroll, berisi minimal nilai Total Akhir dan tombol Checkout.
5. THE Checkout_Page SHALL menggunakan warna dan tipografi dari design system SADITA tanpa mendefinisikan ulang nilai warna secara inline di template.

---

### Requirement 2: Section Alamat Pengiriman

**User Story:** Sebagai customer, saya ingin mengisi alamat pengiriman dengan mudah dan cepat, sehingga pesanan dikirimkan ke lokasi yang tepat.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menyediakan field input berikut dalam section alamat: Nama Lengkap (maks. 100 karakter), Nomor WhatsApp, Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan, Alamat Lengkap (maks. 255 karakter), Kode Pos, dan Catatan (opsional, maks. 500 karakter).
2. WHEN Checkout_Page dimuat, THE Checkout_Page SHALL mengisi dropdown Provinsi dari tabel `provinces` di database lokal tanpa memanggil API eksternal.
3. WHEN customer memilih Provinsi, THE Checkout_Page SHALL memuat daftar Kota/Kabupaten yang sesuai dari tabel `regencies` secara otomatis dan mengosongkan pilihan Kecamatan dan Kelurahan yang sebelumnya dipilih.
4. WHEN customer memilih Kota/Kabupaten, THE Checkout_Page SHALL memuat daftar Kecamatan yang sesuai dari tabel `districts` secara otomatis dan mengosongkan pilihan Kelurahan yang sebelumnya dipilih.
5. WHEN customer memilih Kecamatan, THE Checkout_Page SHALL memuat daftar Kelurahan yang sesuai dari tabel `villages` secara otomatis.
6. WHEN customer memilih Kelurahan, THE Checkout_Page SHALL memicu kalkulasi ongkir secara otomatis.
7. IF customer mencoba submit form dengan field Nama, WhatsApp, Provinsi, Kota, Kecamatan, Kelurahan, atau Alamat Lengkap yang kosong, THEN THE Checkout_Page SHALL menampilkan pesan validasi di bawah field yang bermasalah dan mencegah pengiriman form.
8. IF customer memasukkan Nomor WhatsApp yang tidak terdiri dari 10 hingga 15 digit angka, THEN THE Checkout_Page SHALL menampilkan pesan validasi di bawah field WhatsApp dan mencegah pengiriman form.
9. IF gagal memuat data dropdown (Provinsi, Kota, Kecamatan, atau Kelurahan) dari database, THEN THE Checkout_Page SHALL menampilkan pesan error di bawah dropdown yang gagal dan tombol "Muat Ulang" untuk mencoba kembali.
10. WHERE customer telah login, THE Checkout_Page SHALL mengisi field Nama dan WhatsApp secara otomatis dari data profil customer; jika data profil tidak lengkap, field yang tidak tersedia dibiarkan kosong dan dapat diisi manual.

---

### Requirement 3: Kalkulasi dan Tampilan Ongkir

**User Story:** Sebagai customer, saya ingin melihat biaya ongkir yang akurat setelah memilih alamat, sehingga saya dapat memutuskan metode pengiriman yang sesuai.

#### Acceptance Criteria

1. WHEN customer memilih Kelurahan, THE Checkout_Page SHALL menampilkan Skeleton_Loading pada semua card metode pengiriman selama proses kalkulasi berlangsung; WHEN kalkulasi selesai, Skeleton_Loading SHALL digantikan oleh card metode pengiriman yang sebenarnya.
2. WHILE kalkulasi ongkir sedang berlangsung, THE Checkout_Page SHALL menampilkan teks "Menghitung Ongkir..." di atas area metode pengiriman.
3. THE Shipping_Service SHALL menyelesaikan kalkulasi dan mengembalikan hasil dalam waktu kurang dari 3 detik, diukur dari saat request dikirim hingga response diterima, pada kondisi koneksi stabil dan Ongkir_API merespons normal.
4. WHEN kalkulasi ongkir selesai, THE Checkout_Page SHALL menampilkan setiap metode pengiriman dalam format card yang memuat: nama kurir, nama layanan, estimasi pengiriman dalam format "X hari" atau "X–Y hari", dan harga ongkir dalam format Rupiah.
5. WHERE kota tujuan adalah Makassar atau Maros, THE Shipping_Service SHALL menyertakan opsi Kurir SADITA dan Ambil Langsung sebagai metode pengiriman.
6. WHERE kota tujuan adalah Makassar atau Maros DAN total berat order lebih besar dari atau sama dengan 10.000 gram, THE Shipping_Service SHALL menetapkan ongkir Kurir SADITA menjadi Rp 0 dan menampilkan badge "Gratis" pada card tersebut.
7. WHERE kota tujuan bukan Makassar atau Maros, THE Shipping_Service SHALL mengambil data ongkir dari Ongkir_API (API.co.id).
8. WHEN hasil kalkulasi ongkir tersedia, THE Checkout_Page SHALL menampilkan metode pengiriman dalam urutan: (1) harga paling murah, (2) batas bawah estimasi hari paling kecil jika harga sama, (3) layanan lainnya secara alfabetis.
9. THE Checkout_Page SHALL secara default hanya menampilkan satu metode pengiriman terbaik (paling murah), dengan link "Lihat layanan lain" untuk membuka seluruh daftar.
10. THE Shipping_Service SHALL menggunakan cache berdasarkan kombinasi origin, destination, weight, dan warehouse_id dengan TTL 10 menit.
11. WHEN customer mengubah metode pembayaran, THE Checkout_Page SHALL TIDAK memanggil ulang Ongkir_API.
12. IF Ongkir_API tidak merespons atau mengembalikan error, THEN THE Checkout_Page SHALL mengganti semua Skeleton_Loading dengan pesan error yang menyebutkan bahwa kalkulasi gagal, beserta tombol "Coba Lagi" yang memicu ulang kalkulasi ongkir.

---

### Requirement 4: Section Ringkasan Belanja

**User Story:** Sebagai customer, saya ingin melihat ringkasan belanja yang lengkap dan selalu terkini, sehingga saya dapat memastikan detail pesanan sebelum membayar.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan daftar produk yang dipesan beserta nama produk, kuantitas, dan subtotal per produk (dihitung sebagai harga × kuantitas) dalam section Ringkasan Belanja.
2. THE Checkout_Page SHALL menampilkan Total Berat keseluruhan order dalam satuan gram.
3. THE Checkout_Page SHALL menampilkan nama Gudang (Warehouse) yang akan memproses order.
4. IF customer belum memilih metode pengiriman, THE Checkout_Page SHALL menampilkan placeholder "—" pada baris Kurir, Layanan, dan Estimasi; IF customer telah memilih metode pengiriman, THEN THE Checkout_Page SHALL menampilkan nama Kurir, nama Layanan, dan Estimasi pengiriman sesuai metode yang dipilih.
5. IF customer belum memilih metode pengiriman, THE Checkout_Page SHALL menampilkan placeholder "—" pada baris Ongkir; IF customer telah memilih metode pengiriman, THEN THE Checkout_Page SHALL menampilkan Ongkir sesuai metode pengiriman yang dipilih.
6. THE Checkout_Page SHALL menampilkan Total Pembayaran yang merupakan jumlah subtotal produk ditambah ongkir dikurangi diskon; jika belum ada voucher yang diterapkan, nilai diskon dianggap Rp 0.
7. WHEN customer mengubah metode pengiriman, THE Checkout_Page SHALL memperbarui baris Kurir, Layanan, Estimasi, Ongkir, dan Total Pembayaran dalam waktu kurang dari 300ms tanpa reload halaman.

---

### Requirement 5: Section Voucher (Future-Ready)

**User Story:** Sebagai customer, saya ingin dapat menggunakan voucher diskon, sehingga saya mendapatkan harga yang lebih hemat.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan section Voucher dengan field input teks kode voucher dan tombol "Gunakan".
2. WHILE fitur Voucher belum diaktifkan (sebelum V3.1), THE Checkout_Page SHALL menampilkan section Voucher dalam kondisi `disabled` — field input dan tombol tidak dapat diklik, dan section diberi label visual "Segera Hadir" — tanpa menyembunyikan atau menghapus elemen tersebut dari DOM.
3. THE Checkout_Page SHALL merender section Voucher dari data konfigurasi sehingga pengaktifan fitur cukup dilakukan dengan mengubah nilai konfigurasi, tanpa perubahan struktur template Blade.

---

### Requirement 6: Section Metode Pembayaran

**User Story:** Sebagai customer, saya ingin memilih metode pembayaran dengan mudah dan jelas, sehingga saya dapat menyelesaikan pembayaran sesuai preferensi saya.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan metode pembayaran sebagai daftar card yang dapat dipilih; tidak ada metode yang terpilih secara default saat halaman dimuat; elemen `<select>` tidak boleh digunakan.
2. THE Checkout_Page SHALL menampilkan tiga metode pembayaran berikut: Transfer Bank (Transfer Manual), QRIS (Semua E-Wallet), dan COD (Bayar di Tempat).
3. WHEN customer memilih sebuah card metode pembayaran, THE Checkout_Page SHALL memberikan visual feedback berupa border berwarna primary dan background tint dari warna primary pada card yang dipilih; card yang sebelumnya terpilih SHALL dikembalikan ke tampilan normal.
4. THE Checkout_Page SHALL merender daftar metode pembayaran dari sebuah array konfigurasi terpusat, sehingga penambahan entri baru pada array tersebut secara otomatis menampilkan card baru tanpa perubahan struktur template.
5. IF customer mencoba submit checkout tanpa memilih metode pembayaran, THEN THE Checkout_Page SHALL menampilkan pesan error di dalam section Metode Pembayaran dan menggulir tampilan sehingga section tersebut sepenuhnya terlihat di viewport.

---

### Requirement 7: Section Total Pembayaran

**User Story:** Sebagai customer, saya ingin melihat rincian total pembayaran secara transparan, sehingga saya memahami biaya yang harus dibayarkan.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan rincian Total Pembayaran yang terdiri dari: Subtotal produk, Ongkir, Diskon (jika ada), dan Total Akhir; Total Akhir dihitung sebagai Subtotal + Ongkir − Diskon.
2. THE Checkout_Page SHALL menampilkan nilai Total Akhir menggunakan ukuran font minimal satu tingkat lebih besar dari baris Subtotal, Ongkir, dan Diskon dalam section ini.
3. THE Checkout_Page SHALL menampilkan semua nilai dalam format mata uang Rupiah dengan pemisah ribuan titik dan tanpa desimal (contoh: Rp 210.000).
4. WHEN nilai Diskon adalah nol atau null, THE Checkout_Page SHALL menyembunyikan baris Diskon dari tampilan; WHEN nilai Diskon lebih besar dari nol, THE Checkout_Page SHALL menampilkan baris Diskon dengan nilai ditampilkan sebagai pengurang (contoh: −Rp 10.000).

---

### Requirement 8: Tombol Checkout dan Alur Pembuatan Order

**User Story:** Sebagai customer, saya ingin dapat menyelesaikan checkout dengan satu klik, sehingga order saya segera diproses.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan tombol bertuliskan "Lanjutkan" atau "Checkout" sebagai tombol aksi utama, bukan tombol berlabel "WhatsApp".
2. WHEN customer menekan tombol Checkout, THE Checkout_Page SHALL menonaktifkan tombol dan menampilkan indikator loading.
3. WHEN customer menekan tombol Checkout, THE Order_Service SHALL menjalankan alur berikut secara berurutan: (1) Validasi seluruh input di server, (2) Kalkulasi ulang harga dan ongkir, (3) Verifikasi ketersediaan stok, (4) Buat record Order dan OrderItem dalam satu database transaction, (5) Kurangi stok produk, (6) Generate Invoice dan Success_Token.
4. WHEN Order berhasil dibuat, THE Checkout_Controller SHALL mengarahkan customer ke halaman konfirmasi pembayaran menggunakan Success_Token.
5. IF terjadi kegagalan pada salah satu langkah pembuatan order, THEN THE Order_Service SHALL melakukan rollback seluruh perubahan database; THE Checkout_Page SHALL menampilkan pesan error yang menjelaskan penyebab kegagalan dan mengaktifkan kembali tombol Checkout.
6. IF kalkulasi ulang harga atau ongkir di server menghasilkan nilai yang berbeda dari yang dikirimkan frontend, THEN THE Order_Service SHALL menolak pembuatan order; THE Checkout_Page SHALL menampilkan pesan yang menyebutkan terjadi perubahan harga dan meminta customer untuk memeriksa kembali ringkasan sebelum melanjutkan.

---

### Requirement 9: Validasi Server-Side dan Keamanan

**User Story:** Sebagai admin SADITA, saya ingin semua validasi dilakukan di server, sehingga tidak ada manipulasi harga atau stok oleh customer.

#### Acceptance Criteria

1. THE Order_Service SHALL menghitung ulang subtotal setiap item berdasarkan harga produk terkini dari database, bukan dari data yang dikirimkan frontend.
2. THE Order_Service SHALL memverifikasi bahwa stok tersedia setiap produk di Warehouse yang ditentukan lebih besar dari atau sama dengan jumlah yang dipesan, sebelum membuat order.
3. THE Order_Service SHALL menghitung ulang total berat order berdasarkan berat produk terkini dari database.
4. THE Order_Service SHALL memanggil ulang kalkulasi ongkir di server berdasarkan data alamat dan berat yang telah divalidasi; IF kalkulasi ongkir server gagal, THEN THE Order_Service SHALL menolak pembuatan order dengan pesan yang menyebutkan bahwa ongkir tidak dapat diverifikasi.
5. IF harga yang dihitung ulang server berbeda dari data yang dikirimkan frontend, THEN THE Order_Service SHALL menolak pembuatan order dan mengembalikan response yang memuat daftar item beserta harga yang benar per item.
6. IF stok satu atau lebih produk tidak mencukupi saat order diproses, THEN THE Order_Service SHALL menolak pembuatan order dalam satu response yang menyebutkan nama semua produk yang stoknya tidak mencukupi.
7. THE Checkout_Controller SHALL menggunakan CSRF token pada setiap request form submission.
8. IF satu IP address mengirimkan lebih dari 10 request submit checkout dalam satu menit, THEN THE Checkout_Controller SHALL mengembalikan HTTP 429 dengan pesan "Terlalu banyak percobaan. Silakan coba lagi dalam 60 detik." menggunakan Laravel Rate Limiter.

---

### Requirement 10: Performa dan Loading State

**User Story:** Sebagai customer, saya ingin halaman checkout yang responsif dan cepat, sehingga saya tidak frustrasi menunggu.

#### Acceptance Criteria

1. WHILE kalkulasi ongkir sedang berlangsung, THE Checkout_Page SHALL menampilkan minimal satu Skeleton_Loading berbentuk card di area metode pengiriman, bukan spinner kosong.
2. THE Shipping_Service SHALL mengembalikan hasil kalkulasi ongkir dalam waktu kurang dari 3 detik, diukur dari request dikirim hingga response diterima, pada kondisi koneksi stabil dan Ongkir_API merespons normal.
3. WHEN customer melakukan interaksi lokal (memilih dropdown wilayah, memilih metode pengiriman, memilih metode pembayaran), THE Checkout_Page SHALL menampilkan perubahan visual yang dapat diamati dalam waktu kurang dari 300ms, tanpa menunggu respons server.
4. WHILE koneksi internet customer terputus, THE Checkout_Page SHALL menampilkan indikator offline dari komponen layout SADITA, menonaktifkan tombol Checkout, dan menonaktifkan tombol "Coba Lagi" pada area kalkulasi ongkir.
5. WHEN aksi yang memerlukan komunikasi server dijalankan (kalkulasi ongkir, submit checkout, muat dropdown wilayah), THE Checkout_Page SHALL menampilkan loading state yang sesuai pada elemen yang terdampak hingga respons server diterima.

---

### Requirement 11: Modularitas dan Ekstensibilitas Teknis

**User Story:** Sebagai developer SADITA, saya ingin kode checkout yang modular dan mudah dikembangkan, sehingga fitur baru dapat ditambahkan tanpa merombak keseluruhan sistem.

#### Acceptance Criteria

1. THE Checkout_Page SHALL diimplementasikan sebagai satu Livewire component utama dengan setiap section dirender melalui Blade partial yang terpisah (`@include` atau `<livewire:...>`).
2. THE Order_Service SHALL diimplementasikan sebagai class di `app/Services/OrderService.php` dengan public method yang dapat dipanggil dan diuji secara independen dari Livewire component menggunakan PHPUnit.
3. THE Shipping_Service SHALL diimplementasikan sebagai class di `app/Services/ShippingService.php` yang diinjeksikan ke Livewire component melalui constructor injection, sehingga implementasinya dapat diganti dengan mock atau implementasi alternatif tanpa mengubah kode Livewire component.
4. THE Checkout_Page SHALL TIDAK mengandung API key atau konfigurasi sensitif di source code; semua konfigurasi SHALL dibaca dari file `.env` melalui `config()` helper.
5. THE Checkout_Page SHALL dapat diakses melalui route checkout V3 yang terpisah dari route checkout lama; kedua route SHALL berfungsi secara bersamaan selama masa transisi.
6. THE Checkout_Controller SHALL membungkus seluruh proses pembuatan order dalam `DB::transaction()` sehingga jika terjadi exception, seluruh perubahan database di-rollback secara otomatis.

---

### Requirement 12: Kesiapan Database untuk Fitur Masa Depan

**User Story:** Sebagai CTO SADITA, saya ingin struktur database checkout yang scalable, sehingga fitur-fitur baru dapat diimplementasikan tanpa perlu merancang ulang database.

#### Acceptance Criteria

1. THE Order_Service SHALL menyimpan `warehouse_id` (foreign key ke tabel warehouses) pada setiap record Order.
2. THE Order_Service SHALL menyimpan `payment_method` sebagai kolom VARCHAR pada tabel orders, dengan nilai awal "transfer", "qris", atau "cod", sehingga nilai baru dapat ditambahkan tanpa migrasi perubahan tipe kolom.
3. THE Order_Service SHALL menyimpan `shipping_courier`, `shipping_service`, `shipping_etd`, dan `shipping_cost` sebagai kolom terpisah pada tabel orders.
4. THE Order_Service SHALL menyimpan `discount_amount`, `voucher_code`, dan `cashback_amount` pada tabel orders dengan nilai default 0 atau null, sehingga kolom tersebut siap digunakan tanpa migrasi tambahan saat fitur diaktifkan.
5. THE Order_Service SHALL mencatat `price` (harga satuan saat transaksi) dan `subtotal` (harga × kuantitas saat transaksi) per item di tabel `order_items`, bukan harga dari tabel products saat query dilakukan.

---

### Requirement 13: Pengalaman Pengguna dan Aksesibilitas

**User Story:** Sebagai customer baru yang belum berpengalaman belanja online, saya ingin antarmuka checkout yang mudah dipahami, sehingga saya dapat menyelesaikan pembelian pertama saya tanpa bantuan.

#### Acceptance Criteria

1. THE Checkout_Page SHALL menampilkan elemen `<label>` yang terhubung ke setiap field input melalui atribut `for`/`id`, dan teks label pada setiap card pilihan (metode pengiriman, metode pembayaran).
2. THE Checkout_Page SHALL menampilkan tanda asterisk (`*`) di dalam atau setelah teks label setiap field yang wajib diisi.
3. THE Checkout_Page SHALL menampilkan pesan error dalam bahasa Indonesia tanpa kode teknis, kode HTTP, atau nama class/method; pesan harus dapat dipahami oleh pengguna yang tidak memiliki latar belakang teknis.
4. WHEN terjadi error validasi atau error proses pada checkout, THE Checkout_Page SHALL menggulir tampilan secara otomatis ke elemen error pertama yang terlihat; data yang telah diisi customer pada field lain SHALL dipertahankan.
5. THE Checkout_Page SHALL memastikan setiap elemen interaktif (tombol, card pilihan, link) yang tidak memiliki teks terlihat memiliki atribut `aria-label` yang deskriptif; elemen yang sudah memiliki teks terlihat tidak perlu duplikasi aria-label.
6. THE Checkout_Page SHALL memastikan rasio kontras antara teks dan latar belakang pada tombol utama, label field, dan pesan error memenuhi standar WCAG 2.1 Level AA (minimal 4.5:1 untuk teks normal, 3:1 untuk teks besar).
