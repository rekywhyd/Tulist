# Tulist - Aplikasi Manajemen Tugas

Tulist adalah aplikasi web berbasis Laravel untuk manajemen tugas harian. Aplikasi ini memungkinkan pengguna untuk membuat, mengelola, dan melacak tugas serta subtugas dengan fitur autentikasi, termasuk login melalui Google.

## Deskripsi Proyek

Aplikasi ini dibangun menggunakan framework Laravel 12 dengan frontend yang menggunakan Tailwind CSS, Alpine.js, dan Vite. Fitur utama meliputi:
- **Manajemen Tugas Lanjutan**: Prioritas, tanggal jatuh tempo, subtugas, dan kategorisasi (Hari Ini, Besok, Mendatang, Riwayat, Overdue).
- **Workspace & Kolaborasi**: Buat ruang kerja (workspace), undang anggota, dan kelola peran (Admin/Member) dengan kontrol akses yang aman (RBAC).
- **Komentar Tugas & @Mentions**: Berdiskusi langsung pada tugas dan gunakan tag @mention untuk memanggil anggota lain.
- **Tampilan Jadwal (Schedule)**: Visualisasi tugas berbasis kalender/jadwal untuk mempermudah perencanaan.
- **Sistem Notifikasi**: Pemberitahuan untuk undangan workspace, tag mention, dan aktivitas lainnya.
- **Manajemen Profil**: Personalisasi akun dengan fitur unggah foto profil.
- **Laporan Riwayat**: Rekapitulasi laporan tugas yang telah selesai.
- **Onboarding Tour**: Panduan interaktif bagi pengguna baru.
- **Autentikasi & Keamanan**: Login email/password standar dan integrasi Google OAuth (Laravel Breeze).
- **Dashboard Responsif**: Desain UI modern yang berjalan lancar di desktop dan mobile.

## Persyaratan Sistem

Sebelum menginstal dan menjalankan aplikasi ini, pastikan sistem Anda memenuhi persyaratan berikut:

### Software yang Diperlukan
- **PHP**: Versi 8.2 atau lebih tinggi
- **Composer**: Untuk manajemen dependensi PHP
- **Node.js**: Versi 18 atau lebih tinggi (untuk npm dan Vite)
- **npm**: Biasanya sudah terinstall dengan Node.js
- **Database**: MySQL, PostgreSQL, atau SQLite (disarankan MySQL untuk produksi)
- **Web Server**: Apache atau Nginx (untuk produksi), atau gunakan `php artisan serve` untuk development

### Verifikasi Instalasi
Jalankan perintah berikut untuk memverifikasi instalasi:

```bash
php --version
composer --version
node --version
npm --version
```

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal aplikasi dari awal:

### 1. Clone Repository
```bash
git clone <URL_REPOSITORY_ANDA>
cd tulist
```

### 2. Install Dependensi PHP
```bash
composer install
```
Perintah ini akan mengunduh semua dependensi PHP yang diperlukan dari `composer.json`.

### 3. Konfigurasi Environment
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Atau di Windows:
```bash
copy .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```
Ini akan mengisi `APP_KEY` di file `.env` secara otomatis.

### 5. Konfigurasi Database
Edit file `.env` dan atur konfigurasi database. Contoh untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tulist
DB_USERNAME=root
DB_PASSWORD=your_password
```

Untuk SQLite (lebih sederhana untuk development):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

Jika menggunakan SQLite, buat file database:
```bash
touch database/database.sqlite
```

### 6. Jalankan Migrasi Database
```bash
php artisan migrate
```
Perintah ini akan membuat tabel-tabel yang diperlukan di database berdasarkan file migrasi di `database/migrations/`.

### 7. Install Dependensi JavaScript
```bash
npm install
```
Ini akan mengunduh semua dependensi frontend dari `package.json`.

### 8. Build Assets Frontend
Untuk production:
```bash
npm run build
```

Untuk development (dengan hot reload):
```bash
npm run dev
```

## Konfigurasi Autentikasi Google (Opsional)

Jika Anda ingin menggunakan fitur login melalui Google:

### 1. Buat Proyek di Google Cloud Console
- Kunjungi [Google Cloud Console](https://console.cloud.google.com/)
- Buat proyek baru atau pilih proyek yang ada
- Aktifkan Google+ API

### 2. Buat Credentials OAuth
- Pergi ke "Credentials" di menu sebelah kiri
- Klik "Create Credentials" > "OAuth 2.0 Client IDs"
- Pilih "Web application"
- Tambahkan URI redirect: `http://localhost:8000/auth/google/callback` (sesuaikan dengan domain Anda)

### 3. Konfigurasi di .env
Tambahkan ke file `.env`:
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

## Konfigurasi Email (Opsional)

Aplikasi ini menggunakan email untuk fitur seperti verifikasi email, reset password, dan notifikasi. Secara default, email akan disimpan ke log file. Untuk mengirim email sesungguhnya (misalnya ke Gmail), ikuti langkah-langkah berikut:

### Menggunakan Gmail SMTP

#### 1. Aktifkan 2-Factor Authentication di Gmail
- Kunjungi [Akun Google](https://myaccount.google.com/)
- Pergi ke "Keamanan" > "Verifikasi 2 langkah"
- Aktifkan verifikasi 2 langkah jika belum

#### 2. Buat App Password
- Di halaman "Keamanan", cari "Kata sandi aplikasi"
- Pilih "Mail" dan "Komputer Windows" (atau perangkat lain)
- Salin kata sandi aplikasi yang dihasilkan (16 karakter)

#### 3. Konfigurasi di .env
Tambahkan atau edit konfigurasi berikut di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_gmail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Ganti:
- `your_gmail@gmail.com` dengan alamat Gmail Anda
- `your_app_password` dengan kata sandi aplikasi yang Anda buat (bukan kata sandi akun Gmail biasa)

#### 4. Test Pengiriman Email
Untuk menguji, Anda bisa membuat akun baru atau menggunakan fitur reset password. Email akan dikirim ke alamat yang ditentukan.

**Catatan:** Jika menggunakan Gmail, pastikan akun Gmail Anda tidak memblokir aplikasi less secure. App password sudah cukup aman.

### Menggunakan Mailtrap (untuk Testing)
Untuk testing tanpa mengirim email sesungguhnya:
- Daftar di [Mailtrap](https://mailtrap.io/)
- Buat inbox baru
- Salin kredensial SMTP dari Mailtrap
- Konfigurasi di .env seperti di atas, tapi gunakan host dan kredensial dari Mailtrap

## Menjalankan Aplikasi

### Mode Development
```bash
php artisan serve
```
Aplikasi akan berjalan di `http://localhost:8000`

Jika menggunakan Vite untuk development:
```bash
npm run dev
```

### Mode Production
Pastikan untuk build assets dan konfigurasikan web server (Apache/Nginx) untuk menunjuk ke folder `public`.

## Penggunaan Aplikasi

### Registrasi dan Login
1. Akses halaman utama (`/`)
2. Klik "Register" untuk membuat akun baru
3. Atau login dengan email/password yang sudah ada
4. Opsional: Login dengan Google

### Manajemen Tugas
1. Setelah login, akses `/home` untuk dashboard tugas
2. Klik "Add task or reminder" untuk membuat tugas baru
3. Isi judul, deskripsi, tanggal jatuh tempo, dan prioritas
4. Tambahkan subtugas jika diperlukan
5. Tugas akan muncul di kategori yang sesuai (Today, Tomorrow, Upcoming)

### Fitur Workspaces & Kolaborasi
1. Akses halaman **Workspace** (`/workspace`) untuk membuat ruang kerja baru
2. Undang anggota ke workspace menggunakan alamat email mereka
3. Atur *Role* (peran) anggota sebagai **Admin** (memiliki kontrol penuh terhadap workspace) atau **Member** (akses terbatas sesuai tugas)
4. Tugas yang dibuat di dalam workspace dapat dilihat, diubah (sesuai role), dan didiskusikan bersama

### Fitur Tambahan & Kolaborasi
- **Tampilan Schedule**: Akses menu **Schedule** (`/schedule`) untuk melihat penyebaran tugas dalam format waktu/kalender
- **Komentar & Mentions**: Buka detail tugas untuk menambahkan komentar. Gunakan simbol `@` untuk melakukan *mention* ke anggota workspace
- **Notifikasi**: Cek ikon bel notifikasi di pojok kanan atas untuk menerima undangan workspace atau *mention*
- **Laporan Riwayat**: Hasilkan dan unduh laporan aktivitas tugas di halaman riwayat
- **Manajemen Profil**: Kunjungi halaman profil untuk mengganti foto profil dan mengatur preferensi akun
- **Manajemen Tugas Cepat**: Fitur duplikasi tugas, tambah subtugas, ubah penamaan, hingga tandai selesai dengan mudah

## Struktur Proyek

```
tulist/
├── app/                    # Kode aplikasi Laravel
├── bootstrap/              # Bootstrap aplikasi
├── config/                 # Konfigurasi
├── database/               # Migrasi dan seeders
├── public/                 # Assets publik
├── resources/             # Views dan assets frontend
│   ├── css/
│   ├── js/
│   └── views/
├── routes/                 # Definisi routes
├── storage/                # File storage
├── tests/                  # Unit dan feature tests
├── vendor/                 # Dependensi Composer (akan dibuat setelah install)
├── composer.json
├── package.json
├── artisan
└── README.md
```

## Troubleshooting

### Error: "Class not found"
Jalankan `composer dump-autoload`

### Error Database Connection
Periksa konfigurasi di `.env` dan pastikan database server berjalan.

### Assets tidak dimuat
Jalankan `npm run build` atau `npm run dev`

### Permission Issues
Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis oleh web server.

## Kontribusi

Untuk berkontribusi:
1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.

## Dukungan

Jika ada pertanyaan atau masalah, silakan buat issue di repository GitHub atau hubungi tim development.
