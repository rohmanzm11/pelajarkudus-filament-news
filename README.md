<div align="center">

# 📰 Pelajar Kudus News

**Sistem manajemen berita modern berbasis Laravel & Filament**

<p>
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" height="60" alt="Laravel">
  </a>
  &nbsp;&nbsp;&nbsp;
  <a href="https://filamentphp.com" target="_blank">
    <img src="https://raw.githubusercontent.com/filamentphp/filament/3.x/art/banner.jpg" height="60" alt="Filament">
  </a>
  &nbsp;&nbsp;&nbsp;
  <a href="https://livewire.laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/livewire/livewire/main/art/logo.svg" height="60" alt="Livewire">
  </a>
</p>

<p>
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Filament-FDAE4B?style=for-the-badge&logo=laravel&logoColor=white" alt="Filament">
  <img src="https://img.shields.io/badge/Livewire-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<p>
  <a href="#-fitur">Fitur</a> •
  <a href="#-teknologi">Teknologi</a> •
  <a href="#-instalasi">Instalasi</a> •
  <a href="#-import-post-dari-excel">Import Excel</a> •
  <a href="#-struktur-folder">Struktur</a> •
  <a href="#-kontribusi">Kontribusi</a>
</p>

</div>

---

## 📖 Tentang Proyek

**Pelajar Kudus News** adalah aplikasi portal berita yang dibangun menggunakan **Laravel** dengan panel admin **Filament**. Antarmuka dibuat responsif dengan **Tailwind CSS** dan interaktivitas dinamis dengan **Livewire**. Admin juga bisa mengimpor banyak post sekaligus melalui file **Excel**.

---

## ✨ Fitur

- 🔐 **Panel Admin Filament** – dashboard modern untuk mengelola konten
- 📝 **Manajemen Post/Berita** – tambah, ubah, hapus, dan publikasi berita
- 🗂️ **Kategori & Tag** – pengelompokan berita yang rapi
- 📥 **Import Post dari Excel** – unggah banyak berita sekaligus dalam satu kali proses
- ⚡ **Livewire** – halaman dinamis tanpa reload
- 🎨 **Tailwind CSS** – tampilan responsif di semua perangkat
- 🖼️ **Upload Gambar** – thumbnail untuk setiap berita

---

## 🧰 Teknologi

| Teknologi | Kegunaan |
|-----------|----------|
| [Laravel](https://laravel.com) | Framework backend utama |
| [Filament](https://filamentphp.com) | Panel admin & form builder |
| [Livewire](https://livewire.laravel.com) | Komponen UI dinamis |
| [Tailwind CSS](https://tailwindcss.com) | Styling antarmuka |
| [Vite](https://vitejs.dev) | Bundler aset frontend |
| MySQL / MariaDB | Database |
| Excel Import | Impor data post dari `.xlsx` / `.xls` / `.csv` |

---

## 📋 Persyaratan Sistem

Pastikan sudah terpasang:

- PHP **>= 8.2** (sesuaikan dengan versi Laravel di `composer.json`)
- Composer
- Node.js **>= 18** & NPM
- MySQL / MariaDB
- Ekstensi PHP: `gd`, `zip`, `intl`, `mbstring`, `xml`, `fileinfo`

---

## 🚀 Instalasi

### 1. Clone repository

```bash
git clone https://github.com/rohmanzm11/pelajarkudus-filament-news.git
cd pelajarkudus-filament-news
```

### 2. Install dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Lalu buka file `.env` dan atur database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelajarkudus_news
DB_USERNAME=root
DB_PASSWORD=
```

> 💡 Buat dulu database kosong bernama `pelajarkudus_news` (atau sesuai nama di `.env`).

### 4. Migrasi database

```bash
php artisan migrate
```

Jika tersedia seeder:

```bash
php artisan migrate --seed
```

### 5. Buat symbolic link storage

```bash
php artisan storage:link
```

### 6. Buat akun admin Filament

```bash
php artisan make:filament-user
```

Ikuti instruksi untuk mengisi nama, email, dan password.

### 7. Jalankan aplikasi

Buka dua terminal:

```bash
# Terminal 1 – server Laravel
php artisan serve

# Terminal 2 – compile aset (Tailwind + Vite)
npm run dev
```

Akses aplikasi:

| Halaman | URL |
|---------|-----|
| 🌐 Website | http://localhost:8000 |
| 🔐 Admin Panel | http://localhost:8000/admin |

---

## 📥 Import Post dari Excel

Fitur ini memungkinkan admin menambahkan banyak berita sekaligus.

### Langkah penggunaan

1. Login ke **Admin Panel** (`/admin`)
2. Buka menu **Posts**
3. Klik tombol **Import** di bagian atas tabel
4. Pilih file Excel (`.xlsx`, `.xls`, atau `.csv`)
5. Klik **Import** dan tunggu hingga proses selesai

### Contoh format Excel

> ⚠️ Sesuaikan nama kolom dengan field yang digunakan pada importer di proyek ini.

| title | slug | category | content | thumbnail | status | published_at |
|-------|------|----------|---------|-----------|--------|--------------|
| Judul Berita 1 | judul-berita-1 | Pendidikan | Isi berita pertama... | posts/gambar1.jpg | published | 2025-01-01 10:00:00 |
| Judul Berita 2 | judul-berita-2 | Olahraga | Isi berita kedua... | posts/gambar2.jpg | draft | 2025-01-02 08:30:00 |

### Tips import

- ✅ Baris pertama **wajib** berisi nama kolom (header)
- ✅ Pastikan `slug` unik agar tidak terjadi duplikasi
- ✅ Gunakan format tanggal `YYYY-MM-DD HH:MM:SS`
- ✅ Untuk data besar, jalankan queue worker:

```bash
php artisan queue:work
```

---

## 🗂️ Struktur Folder

```
pelajarkudus-filament-news/
├── app/
│   ├── Filament/
│   │   ├── Resources/      # Resource admin (Post, Category, dll)
│   │   └── Imports/        # Importer Excel
│   ├── Livewire/           # Komponen Livewire
│   └── Models/             # Model Eloquent
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/                # Tailwind CSS
│   ├── js/
│   └── views/              # Blade & Livewire views
├── routes/
│   └── web.php
└── public/
```

---

## 🛠️ Perintah Berguna

```bash
# Bersihkan cache
php artisan optimize:clear

# Build aset untuk production
npm run build

# Jalankan migrasi ulang (HATI-HATI: menghapus semua data)
php artisan migrate:fresh --seed

# Update Filament
composer update filament/filament
php artisan filament:upgrade
```

---

## 🌍 Deployment (Production)

```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
```

Pastikan di `.env` production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com
```

---

## ❓ Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Gambar tidak muncul | Jalankan `php artisan storage:link` |
| Tampilan Filament berantakan | Jalankan `php artisan filament:upgrade` lalu `npm run build` |
| Error `Class not found` | Jalankan `composer dump-autoload` |
| Import Excel gagal | Cek format header kolom & ekstensi PHP `zip` / `gd` |
| Perubahan tidak terlihat | Jalankan `php artisan optimize:clear` |

---

## 🤝 Kontribusi

Kontribusi sangat terbuka!

1. **Fork** repository ini
2. Buat branch baru: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m "Menambahkan fitur ..."`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buat **Pull Request**

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).

---

<div align="center">

Dibuat dengan ❤️ oleh [rohmanzm11](https://github.com/rohmanzm11)

⭐ Jangan lupa beri **star** jika proyek ini bermanfaat!

</div>
