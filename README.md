# SJ Trans (PT Semesta Jaya Transport Indonesia) 🚌

Website portal reservasi sewa bus pariwisata eksekutif dan profesional di Indonesia, terinspirasi oleh standar layanan [TRAC Astra Bus Pariwisata](https://www.trac.astra.co.id/sewa-bus-pariwisata/jakarta).

---

## 🛠️ Tech Stack

- **Frontend**: [Nuxt 4](https://nuxt.com/) (`future.compatibilityVersion: 4`), Vue 3, Tailwind CSS, Lucide Icons.
- **Backend**: [Laravel 13](https://laravel.com/) (PHP 8.3), Laravel Sanctum Token Authentication.
- **Database**: SQLite (Zero-config) / MySQL support.
- **Design System**: Blue Corporate Palette dengan Dynamic Live Theme Switcher.

---

## ✨ Fitur Utama

1. **Autentikasi Fleksibel**:
   - **Login**: Bisa menggunakan **Username** ATAU **Email** + Password.
   - **Sign Up**: Pendaftaran lengkap dengan **Username, Email, No. Telepon / WhatsApp, dan Password**.
   - **3 Role Pengguna**: `super_admin`, `admin`, dan `customer`.
2. **Kalkulator Sewa Instan & Kolom Diskon**:
   - Estimasi tarif transparan berdasarkan rute, tanggal sewa, jenis armada, dan jumlah unit bus.
   - Input kupon promo diskon (contoh: `SJTPROMO10` untuk potongan 10% dan `LIBURANSERU` untuk potongan langsung Rp 350.000).
3. **Katalog Armada Bus Pariwisata**:
   - Big Bus SHD Scania K410IB Jetbus 5 (50 Seat)
   - Big Bus Mercedes-Benz OH 1626 Pariwisata (59 Seat)
   - Medium Bus Jetbus 3+ MD Executive (31 Seat)
   - Toyota HiAce Premio Luxury VIP (14 Seat VIP)
   - Modal detail spesifikasi lengkap, dimensi, fasilitas keselamatan, dan audio karaoke.
4. **Live Chat & Testimoni Pelanggan**:
   - Floating widget live chat interaktif di pojok kanan bawah dengan sambutan otomatis CS dan tombol WhatsApp.
   - Ulasan bintang 5 dengan profil pelanggan korporat dan form kirim ulasan.
5. **Dynamic Theme Color Switcher**:
   - Pilihan preset tema: *Royal Blue (Default), Navy Corporate, Ocean Sapphire, Midnight Executive, Emerald Tour*.
   - Input custom HEX color picker yang mengubah warna aksen website secara real-time.
6. **CRUD Pemesanan & Transaksi**:
   - Penerbitan invoice resmi dengan instruksi transfer bank (BCA & Mandiri).
   - Portal *"Pesanan Saya"* untuk pelanggan (upload bukti transfer DP / Pelunasan).
   - Admin Control Center:
     - Tabel Pemesanan (update status: Pending, Confirmed, Completed, Cancelled).
     - Tabel Transaksi (verifikasi pembayaran bukti transfer).
     - Tabel Armada Bus (CRUD armada & harga).
     - Tabel Diskon (buat dan kelola kode promo).
     - Tabel Kelola Pengguna (khusus Super Admin).
7. **Footer Lengkap**:
   - Nama Perusahaan & Legalitas Kemenhub / Jasa Raharja.
   - Alamat Kantor TB Simatupang & Pool Cibubur KM 26 Jagorawi.
   - Kontak Hotline 24 Jam: `1500-888`, WhatsApp: `0812-8888-7788`, Telp: `021-8899-7700`.
   - Lokasi Peta: Embed Google Maps interaktif.
   - Email: `info@sjtrans.co.id` & `booking@sjtrans.co.id`.
   - Media Sosial: Instagram, Facebook, TikTok, YouTube, LinkedIn.
   - Kemitraan: PT Astra International Tbk, PT Jasa Raharja, Pertamina Patra Niaga, Bank Mandiri, Bank BCA.

---

## 🔑 Akun Demo & Kredensial

| Role | Username | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin` | `superadmin@sjtrans.co.id` | `password123` | Akses penuh seluruh sistem & kelola pengguna |
| **Admin** | `admin` | `admin@sjtrans.co.id` | `password123` | Akses operasional pemesanan, transaksi & armada |
| **Customer** | `budi` | `budi@gmail.com` | `password123` | Riwayat pemesanan saya & upload bukti bayar |

*(Login modal juga menyediakan tombol instan 1-klik untuk akun demo).*

---

## 🚀 Panduan Menjalankan Proyek

### 1. Backend (Laravel 13)
```bash
cd sjtrans-backend

# Jalankan server API
php artisan serve --host=127.0.0.1 --port=8000
```
API berjalan di: `http://127.0.0.1:8000/api/v1`

### 2. Frontend (Nuxt 4)
```bash
cd sjtrans-frontend

# Install dependencies jika baru pertama kali
npm install

# Jalankan Nuxt development server
npm run dev -- --port 3000
```
Website berjalan di: `http://localhost:3000`

---

## 🧪 Menjalankan Test Backend

```bash
cd sjtrans-backend
php artisan test
```
*Hasil: 7 Feature Tests passed (100%), 30 Assertions.*
