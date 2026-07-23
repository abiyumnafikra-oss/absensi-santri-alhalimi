# Sistem Absensi Santri Al-Halimi

Aplikasi absensi santri berbasis web menggunakan QR Code dan RFID yang ringan dan sesuai dengan format baku administrasi pondok pesantren.

## 📋 Fitur Utama

- ✅ Absensi dengan QR Code dan RFID
- ✅ Multi-role (Admin, Guru, Santri, Wali Kelas)
- ✅ Rekam absensi (Hadir, Izin, Sakit, Terlambat, Keluar)
- ✅ Laporan dan Rekap absensi (Harian, Bulanan)
- ✅ Export data ke Excel
- ✅ Cetak ID Card dan Kartu Santri
- ✅ Notifikasi WhatsApp
- ✅ Backup dan Restore Database
- ✅ Manajemen Santri, Kelas, dan Guru
- ✅ Manajemen Profil Sekolah/Pondok

## 🛠️ Persyaratan Teknis

- PHP 7.4 atau lebih baru
- MySQL/MariaDB 5.7 atau lebih baru
- Web Server (Apache, Nginx, dll)
- Browser modern (Chrome, Firefox, Safari, Edge)

## 📦 Instalasi

### 1. Persiapan Database

- Buat database baru dengan nama `absensi_alhalimi`
- Import file SQL: `db-absensi-qr-v5-39-ok.sql`

```bash
mysql -u root -p absensi_alhalimi < db-absensi-qr-v5-39-ok.sql
```

### 2. Konfigurasi Koneksi Database

Edit file `config.php` dengan detail database Anda:

```php
$host = "localhost";      // Host database
$user = "root";            // Username
$pass = "password";        // Password
$db   = "absensi_alhalimi"; // Nama database
```

### 3. Upload ke Server

- Upload semua file ke folder public_html atau www di server Anda
- Buat folder `uploads/` dan `backup/` dengan permission 755

### 4. Akses Aplikasi

Buka browser dan akses: `http://localhost/absensi-santri-alhalimi/`

## 🔐 Login Default

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Guru | guru | guru123 |
| Wali Kelas | wali | wali123 |

**⚠️ Penting:** Ubah password default setelah login pertama kali!

## 📱 Fitur Per Role

### Admin
- Dashboard statistik
- Manajemen santri
- Manajemen kelas
- Manajemen guru/staff
- Pengaturan profil pondok
- Backup dan restore database
- Laporan lengkap

### Guru/Wali Kelas
- Rekam absensi
- Lihat laporan absensi
- Cetak kartu santri
- Export data

### Santri
- Lihat riwayat absensi
- Lihat profil pribadi
- Lihat pengumuman

## 🎨 Customisasi UI

Untuk mengubah tampilan UI:

1. Edit file CSS di folder `assets/css/`
2. Ubah logo dan background di `assets/img/`
3. Sesuaikan warna dan font sesuai kebutuhan
4. Edit template HTML di setiap file PHP

## 📝 Data yang Perlu Diinput

Setelah instalasi, Anda perlu mengisi data berikut:

1. **Profil Pondok** → Menu Pengaturan > Profil Sekolah
   - Nama pondok pesantren
   - Alamat lengkap
   - Nama kepala/direktur
   - Logo pondok
   - Jam masuk dan jam pulang

2. **Data Santri** → Menu Santri
   - Import dari file Excel atau input manual
   - Foto santri
   - RFID/QR Code

3. **Data Kelas**
   - Nama kelas/tingkat
   - Jumlah santri

4. **Data Guru/Staff**
   - Nama guru/staff
   - Jabatan
   - NIP

## 🆘 Troubleshooting

### Error "Koneksi gagal"
- Pastikan MySQL running
- Cek konfigurasi di `config.php`
- Pastikan database sudah dibuat

### Error "Upload gagal"
- Cek folder `uploads/` memiliki permission 755
- Cek ukuran file upload

### Laporan tidak muncul
- Pastikan file FPDF dan PHPExcel ada di folder
- Cek permission folder

## 📄 License

Gratis untuk digunakan dengan tujuan pendidikan dan sosial.

## 🤝 Kontribusi

Untuk saran dan perbaikan, silakan buat issue atau pull request.

---

**Catatan:** Aplikasi ini dikembangkan untuk keperluan absensi santri di Pondok Pesantren Al-Halimi. Silakan sesuaikan sesuai kebutuhan institusi Anda.
