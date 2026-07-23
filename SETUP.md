# Panduan Setup Sistem Absensi Santri Al-Halimi

## 🚀 Panduan Lengkap Setup untuk Pemula

### Prerequisites
- Xampp atau Wamp atau Lamp sudah terinstall
- MySQL/MariaDB sudah berjalan
- Editor teks (VS Code, Notepad++, dll)

---

## ✅ Step 1: Persiapan Database

### A. Buat Database Baru

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik tombol **New** atau **Buat**
3. Isi nama database: `absensi_alhalimi`
4. Pilih Collation: `utf8mb4_general_ci`
5. Klik **Create**

### B. Import File SQL

1. Pilih database `absensi_alhalimi`
2. Klik tab **Import**
3. Pilih file: `db-absensi-qr-v5-39-ok.sql`
4. Klik **Go/Execute**

Selesai! Database sudah siap dengan tabel dan data awal.

---

## ✅ Step 2: Konfigurasi Koneksi Database

1. Buka file `config.php` dengan editor teks
2. Sesuaikan dengan konfigurasi server Anda:

```php
$host = "localhost";      // Biasanya localhost
$user = "root";            // Username MySQL (default: root)
$pass = "";                // Password MySQL (default: kosong untuk Xampp)
$db   = "absensi_alhalimi"; // Nama database yang sudah dibuat
```

3. Simpan file

**Contoh untuk berbagai konfigurasi:**

```php
// Untuk Xampp (default)
$host = "localhost";
$user = "root";
$pass = "";

// Untuk Wamp
$host = "localhost";
$user = "root";
$pass = "";

// Untuk hosting production
$host = "server.hosting.com"; // Ubah sesuai hosting
$user = "user_hosting";       // Username dari hosting
$pass = "password_hosting";   // Password dari hosting
```

---

## ✅ Step 3: Upload File ke Server

### Local (Xampp/Wamp/Lamp)

1. Ekstrak file aplikasi
2. Letakkan di folder:
   - **Xampp:** `C:\xampp\htdocs\absensi-santri-alhalimi\`
   - **Wamp:** `C:\wamp64\www\absensi-santri-alhalimi\`
   - **Linux (Lamp):** `/var/www/html/absensi-santri-alhalimi/`

### Hosting (FTP)

1. Hubungkan via FTP client (FileZilla, WinSCP, dll)
2. Upload ke folder `public_html/` atau `www/`
3. Folder struktur: `public_html/absensi-santri-alhalimi/`

---

## ✅ Step 4: Buat Folder Penting

Pastikan folder berikut ada dengan permission 755:

```
absensi-santri-alhalimi/
├── uploads/        (untuk foto santri)
├── backup/         (untuk backup database)
├── assets/
├── fpdf/
└── phpexcel/
```

Jika belum ada, buat folder `uploads/` dan `backup/`

---

## ✅ Step 5: Akses Aplikasi

### Local
```
http://localhost/absensi-santri-alhalimi/
```

### Hosting
```
http://domainanda.com/absensi-santri-alhalimi/
```

---

## 🔐 Step 6: Login Pertama Kali

### Akun Default

| Role | Username | Password | Fungsi |
|------|----------|----------|--------|
| **Admin** | admin | admin123 | Kelola semua |
| **Guru** | guru | guru123 | Rekam absensi |
| **Wali Kelas** | wali | wali123 | Laporan kelas |

### Langkah Login

1. Buka halaman login aplikasi
2. Masukkan username dan password
3. Klik tombol **Login**

**⚠️ PENTING:** Ubah password default setelah login:
1. Klik menu **Profil** atau **Settings**
2. Pilih **Ubah Password**
3. Masukkan password baru
4. Simpan

---

## 📝 Step 7: Input Data Awal

### 7.1 Profil Pondok Pesantren

1. Login sebagai **Admin**
2. Buka menu **Pengaturan** > **Profil Sekolah**
3. Isi data berikut:
   - **Nama Pondok:** Nama pondok pesantren Anda
   - **Alamat:** Alamat lengkap
   - **Kepala Pondok:** Nama pimpinan
   - **NIP Kepala:** Nomor NIP
   - **Logo:** Upload logo pondok (ukuran ~200x200px)
   - **Jam Masuk:** Contoh: 06:00 AM
   - **Jam Pulang:** Contoh: 03:00 PM
4. Klik **Simpan**

### 7.2 Input Data Santri

Ada 2 cara:

#### Cara 1: Import dari Excel (Cepat)

1. Buka menu **Santri** > **Import Santri**
2. Download template file `template_siswa.xlsx`
3. Isi data santri di Excel sesuai template
4. Upload file Excel
5. Klik **Import**

**Format Excel:**
```
| NIS | NISN | Nama | Kelas | No. WA |
|-----|------|------|-------|--------|
|1001 |1010101010| Ahmad Santri | 1A | 628123456789 |
|1002 |1010101011| Budi Santri | 1A | 628123456790 |
```

#### Cara 2: Input Manual (Satu per satu)

1. Buka menu **Santri**
2. Klik tombol **Tambah Santri**
3. Isi form:
   - **NIS:** Nomor Induk Santri
   - **NISN:** Nomor NISN
   - **Nama:** Nama lengkap santri
   - **Kelas:** Pilih kelas
   - **No. WA:** Nomor WhatsApp orang tua
   - **Foto:** Upload foto santri
4. Klik **Simpan**

### 7.3 Input Data Kelas

1. Buka menu **Kelas** atau **Manajemen Kelas**
2. Klik **Tambah Kelas**
3. Isi:
   - **Nama Kelas:** Contoh: 1A, 1B, 2A, dll
   - **Wali Kelas:** Pilih guru/ustadz
4. Klik **Simpan**

### 7.4 Input Data Guru/Staff

1. Buka menu **Guru** atau **Manajemen Staff**
2. Klik **Tambah Guru**
3. Isi:
   - **Nama:** Nama guru/staff
   - **NIP:** Nomor identitas
   - **Jabatan:** Guru, Ustadz, dll
   - **Username:** Untuk login (opsional)
   - **Password:** Untuk login (opsional)
4. Klik **Simpan**

---

## 🎨 Step 8: Customisasi UI (Opsional)

### Ubah Warna Tema

1. Buka file `assets/css/style.css`
2. Cari dan ubah variabel warna:

```css
/* Warna Primary (Biru default) */
:root {
    --primary-color: #007bff;    /* Ubah ke warna favorit Anda */
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
}
```

### Ubah Logo

1. Siapkan file logo (format PNG/JPG, ukuran ~200x200px)
2. Letakkan di folder `assets/img/`
3. Edit file HTML header yang menggunakan logo
4. Ubah nama file di tag `<img src="assets/img/logo.png">`

---

## ✨ Step 9: Mulai Menggunakan

### Admin
- Dashboard → Lihat statistik absensi
- Manajemen Santri → Kelola data santri
- Laporan → Generate laporan bulanan
- Pengaturan → Konfigurasi sistem

### Guru/Wali Kelas
- Absensi → Scan QR Code atau input manual
- Laporan → Lihat laporan kelas
- Cetak → Cetak kartu santri

### Santri
- Profil → Lihat data pribadi
- Riwayat → Lihat riwayat absensi

---

## 🆘 Troubleshooting

### Problem: "Error: Koneksi database gagal"

**Solusi:**
1. Pastikan MySQL/MariaDB running
2. Cek konfigurasi `config.php`
3. Pastikan database `absensi_alhalimi` sudah dibuat
4. Test koneksi dengan phpMyAdmin

### Problem: "Upload foto gagal"

**Solusi:**
1. Buat folder `uploads/` jika belum ada
2. Set permission folder ke 755
3. Cek ukuran file (maks 5MB)
4. Cek format file (PNG, JPG, JPEG)

### Problem: "Database corrupt/error"

**Solusi:**
1. Buka phpMyAdmin
2. Pilih database `absensi_alhalimi`
3. Klik **Check all** → **With selected: Repair table**

### Problem: "Laporan tidak muncul"

**Solusi:**
1. Pastikan folder `fpdf/` dan `phpexcel/` ada
2. Cek permission folder
3. Test dengan membuat laporan baru

---

## 📞 Dukungan

Jika ada masalah:
1. Cek dokumentasi ini kembali
2. Lihat error message yang muncul
3. Cek file `config.php` lagi
4. Hubungi administrator hosting Anda

---

**Selamat! Sistem absensi santri Anda sudah siap digunakan! 🎉**
