## 🎯 Tentang Proyek

**VitaCheck Unila** adalah platform digital terintegrasi untuk manajemen pemeriksaan kesehatan mahasiswa Universitas Lampung. Aplikasi ini memudahkan mahasiswa untuk:

-   Melakukan pemesanan tes kesehatan secara online
-   Memilih slot waktu yang sesuai jadwal
-   Mengunggah dokumen persyaratan
-   Melacak status pemesanan secara real-time
-   Mengakses hasil tes dalam format digital

Platform ini dirancang dengan fokus pada **user experience**, **keamanan data**, dan **integrasi dengan sistem klinik kampus**.

### 🎓 Target Pengguna

-   **Mahasiswa Universitas Lampung** - Pengguna utama platform
-   **Admin Klinik** - Mengelola jadwal, tes, dan hasil
-   **Admin Universitas** - Monitoring keseluruhan sistem
-   **Staf Klinik** - Input data hasil tes

---

## ✨ Fitur Utama

### 1. **Manajemen Akun Pengguna**

-   ✅ Registrasi dengan validasi email kampus
-   ✅ Login dengan OTP (One-Time Password)
-   ✅ Profile management lengkap
-   ✅ Reset password self-service
-   ✅ Two-factor authentication (2FA)
-   ✅ Riwayat login dan aktivitas

### 2. **Pemesanan Tes Kesehatan**

-   ✅ Pilih jenis tes dari katalog lengkap
-   ✅ Sistem slot booking otomatis
-   ✅ Validasi persyaratan dokumen
-   ✅ Upload KTM, bukti pembayaran, dan dokumen lainnya
-   ✅ Konfirmasi pemesanan dengan QR code
-   ✅ Notifikasi email otomatis

### 3. **Tracking & Monitoring**

-   ✅ Status real-time pemesanan (Pending, Approved, Completed, etc)
-   ✅ Dashboard dengan statistik lengkap
-   ✅ Timeline history perubahan status
-   ✅ Notifikasi push dan email
-   ✅ Export riwayat pemesanan

### 4. **Hasil Tes Digital**

-   ✅ Upload hasil tes oleh klinik
-   ✅ Validasi dan verifikasi hasil
-   ✅ Format digital PDF/image
-   ✅ Download hasil untuk keperluan lainnya
-   ✅ Arsip hasil selamanya

### 5. **Manajemen Admin**

-   ✅ Dashboard analytics komprehensif
-   ✅ Kelola jenis tes dan harga
-   ✅ Atur jadwal dan slot ketersediaan
-   ✅ Kelola dokumen persyaratan
-   ✅ Upload dan verifikasi hasil
-   ✅ Laporan terstruktur

### 6. **Keamanan & Compliance**

-   ✅ Enkripsi data end-to-end
-   ✅ GDPR & regulasi privasi data
-   ✅ Audit log lengkap
-   ✅ Role-based access control (RBAC)
-   ✅ Rate limiting & DDoS protection
-   ✅ Backup otomatis harian

---

## 🛠️ Teknologi yang Digunakan

### Backend

```
Laravel 10.x          - Framework PHP modern
MySQL 8.0            - Database relasional
Laravel Sanctum       - API authentication
Laravel Queue         - Job processing
Redis                 - Caching & sessions
Mailgun               - Email delivery service
```

### Frontend

```
Tailwind CSS 3.x     - Utility-first CSS framework
Alpine.js            - Lightweight interactivity
Blade Template       - Server-side templating
Livewire             - Real-time components
Chart.js             - Visualization dashboard
```

### DevOps & Tools

```
Laragon              - Local development environment
Git                  - Version control
Docker               - Containerization (optional)
PHPUnit              - Unit testing
GitHub Actions       - CI/CD pipeline
```

### External Services

```
Mailgun              - Email service
AWS S3               - File storage (optional)
Stripe/Midtrans      - Payment gateway (future)
Twilio               - SMS gateway (optional)
```

---

## 🚀 Instalasi

### Prerequisites

Sebelum memulai, pastikan Anda sudah memiliki:

-   **PHP 8.1+** ([Download](https://www.php.net/downloads))
-   **Composer** ([Download](https://getcomposer.org/))
-   **Node.js 16+** ([Download](https://nodejs.org/))
-   **MySQL 8.0+** ([Download](https://www.mysql.com/))
-   **Git** ([Download](https://git-scm.com/))
-   **Laragon** ([Download](https://laragon.org/)) - Optional tapi recommended

### Step 1: Clone Repository

```bash
# Clone dari repository
git clone https://github.com/yourusername/vitacheck-unila.git

# Masuk ke direktori proyek
cd vitacheck-unila
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Compile assets
npm run dev
```

### Step 3: Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

```bash
# Buat database baru
# Buka MySQL dan jalankan: CREATE DATABASE vitacheck_unila;

# Run migrations
php artisan migrate

# Seed data awal
php artisan db:seed
```

### Step 5: Storage Link

```bash
# Create symbolic link untuk file uploads
php artisan storage:link
```

### Step 6: Queue Setup (Optional)

```bash
# Jalankan queue worker untuk email/notifikasi
php artisan queue:work --daemon
```

### Step 7: Jalankan Application

```bash
# Development server
php artisan serve

# Akses di browser: http://localhost:8000
```

---


#### `config/auth.php` - Konfigurasi Autentikasi

```php
return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
];
```

---

## 📖 Penggunaan

### Untuk Mahasiswa

#### 1. Registrasi

```
1. Klik "Daftar Gratis" di halaman beranda
2. Isi email kampus dan password
3. Verifikasi email
4. Login dengan kredensial Anda
```

#### 2. Buat Pemesanan Baru

```
1. Masuk ke Dashboard
2. Klik "Pesan Tes Kesehatan"
3. Pilih jenis tes yang diinginkan
4. Pilih tanggal dan slot waktu
5. Upload dokumen persyaratan (KTM, bukti pembayaran)
6. Konfirmasi pemesanan
7. Tunggu approval dari klinik
```

#### 3. Lacak Status

```
1. Buka Dashboard
2. Lihat daftar pemesanan dengan status real-time
3. Klik pada pemesanan untuk melihat detail
4. Download hasil jika sudah tersedia
```

### Untuk Admin Klinik

#### 1. Kelola Jadwal Tes

```
Navigasi: Admin > Tes & Jadwal > Kelola Jadwal
- Tentukan tanggal dan waktu tes
- Set jumlah slot per sesi
- Assign staf medis
```

#### 2. Review Pemesanan

```
Navigasi: Admin > Pemesanan > Review
- Lihat dokumen yang diunggah
- Approve atau reject pemesanan
- Kirim notifikasi ke mahasiswa
```

#### 3. Input Hasil Tes

```
Navigasi: Admin > Hasil > Upload Hasil
- Pilih pemesanan yang sudah selesai
- Upload file hasil (PDF/Image)
- Verifikasi dan publish hasil
```

#### 4. Generate Laporan

```
Navigasi: Admin > Laporan
- Laporan harian/mingguan/bulanan
- Export ke Excel/PDF
- Analisis statistik kesehatan
```

---

## 🙏 Terima Kasih

Terima kasih telah menggunakan VitaCheck Unila. Kami terus berinovasi untuk memberikan layanan terbaik bagi mahasiswa Universitas Lampung.

**Dibuat dengan ❤️ untuk kesehatan mahasiswa Universitas Lampung**

---

## 📚 Referensi & Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [Tailwind CSS](https://tailwindcss.com/)
-   [MySQL Documentation](https://dev.mysql.com/doc/)
-   [RESTful API Best Practices](https://restfulapi.net/)
-   [OWASP Security Guidelines](https://owasp.org/)

---

**Last Updated:** Desember 2025
**Version:** 1.0.0
