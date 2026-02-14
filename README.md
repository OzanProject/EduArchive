# EduArchive - Sistem Informasi Manajemen Arsip & Administrasi Sekolah

![EduArchive Banner](public/adminlte/dist/img/AdminLTELogo.png)

**EduArchive** adalah platform berbasis web yang dirancang untuk membantu Dinas Pendidikan dan Sekolah dalam mengelola arsip digital, data akademik, dan administrasi secara terpusat, aman, dan efisien.

Aplikasi ini menggunakan arsitektur **Multi-Tenancy**, memungkinkan satu instalasi aplikasi melayani banyak sekolah dengan database dan subdomain/path yang terisolasi.

## 🚀 Fitur Utama

### 🏢 Untuk Dinas Pendidikan (Super Admin)
- **Manajemen Multi-Tenant**: Kelola pendaftaran dan data ribuan sekolah dalam satu dashboard.
- **Manajemen Jenjang Sekolah**: Konfigurasi dinamis untuk jenjang (SD, SMP, SMA, SMK, dll).
- **Pengaturan Aplikasi Terpusat**: Ubah nama aplikasi, logo, dan landing page secara real-time.
- **Monitoring**: Pantau statistik arsip dan keaktifan sekolah.

### 🏫 Untuk Sekolah (Tenant)
- **Arsip Digital**: Simpan dan kelola Ijazah, SKHUN, dan dokumen penting lainnya.
- **Manajemen Surat**: Pencatatan Surat Masuk dan Surat Keluar yang terintegrasi.
- **Data Siswa & Guru**: Manajemen database civitas akademika.
- **Laporan Otomatis**: Generate laporan ke dinas secara mudah.
- **Branding Mandiri**: Login page khusus untuk setiap sekolah (`domain.com/kode_sekolah`).

## 🛠️ Teknologi yang Digunakan

- **Framework**: [Laravel 12](https://laravel.com)
- **Multi-Tenancy**: [Stancl/Tenancy](https://tenancyforlaravel.com)
- **Database**: MySQL (Menggunakan mode *Tenant Database Isolation*)
- **Frontend**: Blade, Tailwind CSS, AdminLTE 3 (Bootstrap 4)
- **Icons**: FontAwesome & Material Symbols
- **PHP**: ^8.2

## 📦 Instalasi & Konfigurasi

Ikuti langkah berikut untuk menjalankan project di local environment:

### 1. Clone Repository
```bash
git clone https://github.com/username/EduArchive.git
cd EduArchive
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Atur konfigurasi database di `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_eduarchive
DB_USERNAME=root
DB_PASSWORD=
```

⚠️ **Penting untuk Multi-Tenancy:**
Pastikan user database memiliki hak akses `CREATE DATABASE`, karena sistem akan membuat database baru secara otomatis setiap kali ada sekolah mendaftar.

### 4. Generate Key & Migrate
```bash
php artisan key:generate
php artisan migrate --seed
```
*Command ini akan membuat database pusat dan mengisi data awal (Role, Super Admin).*

### 5. Jalankan Aplikasi
```bash
npm run build
php artisan serve
```
Akses aplikasi di: `http://localhost:8000`

## 🔐 Akun Demo

### Super Admin (Dinas)
- **URL**: `http://localhost:8000/login`
- **Email**: `admin@dinas.gov.id` (Sesuaikan dengan seeder)
- **Password**: `password`

### Admin Sekolah (Tenant)
Setelah mendaftarkan sekolah baru di menu Register:
- **URL**: `http://localhost:8000/{kode_sekolah}/login`
- **Email**: (Sesuai input saat registrasi)
- **Password**: (Sesuai input saat registrasi)

## 📂 Struktur Multi-Tenancy

Project ini menggunakan pendekatan **Path-Based identification** untuk Tenant (Sekolah).
- **Portal Pusat**: `domain.com` (Landing Page, Login Dinas, Register Sekolah)
- **Portal Sekolah**: `domain.com/smpn1`, `domain.com/sman1jakarta`

---

Built with ❤️ for Education.
