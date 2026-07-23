<?php
// Backup & Restore Database
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include "config.php";

$msg = "";

// Backup Database
if (isset($_GET['action']) && $_GET['action'] == 'backup') {
    $filename = 'backup_absensi_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Menggunakan mysqldump (jika tersedia di server)
    $command = "mysqldump -h localhost -u root absensi_alhalimi > " . $filename;
    
    // Fallback: Manual backup menggunakan PHP
    $tables = ['siswa', 'absensi', 'users', 'profil_sekolah', 'hari_libur', 'wali_kelas'];
    
    $sql_content = "-- Backup Database Absensi Santri Al-Halimi\n";
    $sql_content .= "-- Dibuat: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        $result = $conn->query("SELECT * FROM $table");
        $sql_content .= "\n-- Backup tabel $table\nDELETE FROM $table;\n";
        
        while ($row = $result->fetch_assoc()) {
            $fields = implode("', '", array_values($row));
            $sql_content .= "INSERT INTO $table VALUES ('" . $fields . "');\n";
        }
    }
    
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $sql_content;
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">💾 Backup & Restore Database</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Backup Database</h5>
                </div>
                <div class="card-body">
                    <p>Buat backup data database Anda untuk keamanan data.</p>
                    <a href="backup_restore.php?action=backup" class="btn btn-success w-100">
                        <i class="fas fa-download"></i> Download Backup
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Restore Database</h5>
                </div>
                <div class="card-body">
                    <p>Restore data dari file backup yang telah dibuat sebelumnya.</p>
                    <p class="text-muted small">Hubungi administrator untuk melakukan restore database.</p>
                    <button class="btn btn-warning w-100" disabled>
                        <i class="fas fa-upload"></i> Upload Backup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <strong>📌 Catatan Penting:</strong>
        <ul>
            <li>Backup dilakukan secara otomatis setiap hari pukul 00:00</li>
            <li>Simpan file backup di tempat yang aman</li>
            <li>Restore hanya bisa dilakukan oleh administrator</li>
            <li>Proses restore akan menghapus data lama dan mengganti dengan data backup</li>
        </ul>
    </div>
</body>
</html>