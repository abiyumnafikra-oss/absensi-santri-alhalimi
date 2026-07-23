<?php
// ===================================
// KONFIGURASI DATABASE
// ===================================
// Edit sesuai dengan konfigurasi server Anda

$host = "localhost";      // Nama host/server
$user = "root";            // Username database
$pass = "";                // Password database
$db   = "absensi_alhalimi"; // Nama database

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset UTF-8
mysqli_set_charset($conn, "utf8mb4");
?>