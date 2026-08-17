# Panduan Deploy Laravel (SADITA) ke cPanel via GitHub

Secara keseluruhan, sistem SADITA saat ini sudah sangat matang untuk diluncurkan (MVP - *Minimum Viable Product*). Fitur-fitur fundamental mulai dari katalog, keranjang, kalkulasi ongkir volumetrik, integrasi multi-gudang, hingga proteksi keamanan stok (transaksi database) semuanya **sudah rampung dan beroperasi sempurna**.

Berikut adalah panduan **Step-by-Step** mengorbitkan proyek SADITA ke cPanel menggunakan GitHub, **termasuk setting Auto-Deploy agar saat Anda push ke GitHub, server langsung terupdate otomatis!**

---

## Tahap 1: Persiapan di cPanel
1. **Pastikan Versi PHP:** Buka menu **Select PHP Version** di cPanel, pastikan Anda menggunakan minimal **PHP 8.2** atau **8.3** (sesuai kebutuhan Laravel 11).
2. **Aktifkan Ekstensi PHP:** Pastikan ekstensi `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `mbstring`, `pdo`, `tokenizer`, `xml`, dan `zip` dalam keadaan tercentang.
3. **Buat Database:** Buka menu **MySQL Databases**. Buat satu buah *Database* dan satu buah *Database User*. Jangan lupa hubungkan *User* ke *Database* tersebut dan berikan hak akses **ALL PRIVILEGES**. Catat nama DB, username, dan password-nya.

## Tahap 2: Integrasi GitHub dengan cPanel
Kita akan menggunakan fitur bawaan cPanel agar server bisa menarik kode secara langsung dari GitHub Anda.

1. Buka menu **Terminal** atau **SSH Access** di cPanel.
2. Buat SSH Key baru dengan perintah ini (tekan *Enter* terus hingga selesai):
   ```bash
   ssh-keygen -t rsa -b 4096 -C "cpanel-deploy"
   ```
3. Tampilkan isi kunci publik Anda:
   ```bash
   cat ~/.ssh/id_rsa.pub
   ```
4. **Copy teks** panjang yang muncul.
5. Buka **GitHub.com** > Masuk ke Repository SADITA Anda > Klik **Settings** > Pilih **Deploy keys** (di menu kiri).
6. Klik **Add deploy key**, beri nama (misal: "cPanel Server"), *Paste* kunci yang tadi dicopy, lalu klik **Add key**.

## Tahap 3: Kloning Repository ke cPanel
1. Kembali ke Beranda cPanel, cari menu **Git Version Control**.
2. Klik tombol **Create**.
3. **Clone URL:** Masukkan URL SSH dari repository GitHub Anda (contoh: `git@github.com:username/sadita-v2.git`).
4. **Repository Path:** Ketik `sadita-v2` (Sistem akan membuat folder ini sejajar dengan `public_html`). **Jangan ditaruh di dalam `public_html` demi keamanan**.
5. Klik **Create**. Tunggu beberapa saat hingga cPanel selesai mengunduh kode Anda.

## Tahap 4: Konfigurasi Laravel
1. Buka menu **Terminal** di cPanel. Masuk ke folder proyek Anda:
   ```bash
   cd sadita-v2
   ```
2. Instal pustaka PHP (Composer):
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. Salin file *environment*:
   ```bash
   cp .env.example .env
   ```
4. Buka menu **File Manager** cPanel. Edit file `.env` di dalam folder `sadita-v2`. Ubah bagian ini:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com

   DB_DATABASE=nama_database_cpanel_anda
   DB_USERNAME=user_database_cpanel_anda
   DB_PASSWORD=password_database_cpanel_anda
   ```
5. Kembali ke **Terminal**, jalankan perintah berikut secara berurutan:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   ```

## Tahap 5: Trik Menghubungkan ke Domain (Symlink)
Laravel menyimpan file publiknya di dalam folder `public`, sedangkan cPanel membaca web dari folder `public_html`. Kita perlu membuat "jalan pintas" (Symlink) agar cPanel membaca folder `public` Laravel.

1. Buka **Terminal** cPanel.
2. (Opsional) Jika folder `public_html` Anda saat ini ada isinya, hapus atau *rename* dulu:
   ```bash
   mv public_html public_html_old
   ```
3. Buat *Symlink* dari folder proyek ke `public_html`:
   ```bash
   ln -s /home/username_cpanel_anda/sadita-v2/public /home/username_cpanel_anda/public_html
   ```
   *(Ganti `username_cpanel_anda` dengan username login cPanel Anda).*
4. Buat tautan untuk penyimpanan gambar/dokumen Laravel:
   ```bash
   cd sadita-v2
   php artisan storage:link
   ```

---

## 🚀 TAHAP 6: SETUP AUTO-DEPLOY (Otomatis Update saat Push)

Agar Anda tidak perlu menekan tombol "Pull" berulang kali di cPanel setiap kali ada update, kita akan menggunakan metode **GitHub Webhook**. Konsepnya: Saat Anda push kode ke GitHub, GitHub akan otomatis 'memanggil' script rahasia di server Anda untuk menarik kode tersebut secara gaib.

### 1. Buat Script Webhook di Server
1. Masuk ke **File Manager** cPanel.
2. Buka folder `sadita-v2/public`.
3. Buat file baru dengan nama `deploy.php`.
4. Isi dengan kode berikut:
   ```php
   <?php
   // Rahasia agar script tidak bisa ditembak sembarangan orang
   $secret = 'RAHASIA_SADITA_123'; 

   if (!isset($_GET['token']) || $_GET['token'] !== $secret) {
       die('Akses ditolak.');
   }

   // Folder root Laravel (berada satu level di atas folder public)
   $projectPath = realpath(__DIR__ . '/..');

   echo "<pre>";
   echo "🚀 Memulai Deployment...\n\n";

   chdir($projectPath);

   // Rentetan perintah otomatis
   $commands = [
       'git pull origin main 2>&1', // Menarik kode terbaru dari GitHub
       'composer install --no-dev --optimize-autoloader 2>&1', // Instal package jika ada yang baru
       'php artisan migrate --force 2>&1', // Migrasi database jika ada tabel baru
       'php artisan optimize:clear 2>&1', // Hapus cache
       'php artisan optimize 2>&1', // Re-cache ulang untuk performa
   ];

   foreach ($commands as $command) {
       echo "Menjalankan: $command\n";
       echo shell_exec($command) . "\n";
       echo "---------------------------------\n";
   }

   echo "✅ Deployment Selesai!\n";
   echo "</pre>";
   ?>
   ```
   *(Penting: Ganti `RAHASIA_SADITA_123` dengan password unik/acak sesuka Anda).*

### 2. Hubungkan GitHub dengan Webhook Server
1. Buka Repository Anda di **GitHub**.
2. Masuk ke **Settings** > **Webhooks** (ada di panel kiri).
3. Klik tombol **Add webhook**.
4. Isi **Payload URL** dengan URL script yang baru Anda buat, misalnya:
   `https://domain-anda.com/deploy.php?token=RAHASIA_SADITA_123`
5. Isi **Content type** dengan `application/json`.
6. Bagian *Which events would you like to trigger this webhook?*, biarkan pada pilihan **Just the push event.**
7. Klik **Add webhook**.

---

## 🎉 SELESAI! BAGAIMANA ALUR KERJANYA SEKARANG?

1. Anda sedang santai ngoding atau memperbaiki bug di laptop Anda.
2. Setelah beres, Anda mengetikkan perintah sakti: 
   `git add .` -> `git commit -m "fix bug"` -> `git push origin main`.
3. Dalam hitungan detik, perubahan tersebut tidak hanya masuk ke GitHub, tapi juga otomatis ditarik *(pulled)* oleh server cPanel Anda.
4. Pengunjung website `https://domain-anda.com` akan langsung melihat versi terbaru tanpa ada campur tangan manual lagi dari Anda di cPanel! Canggih bukan? 🚀
