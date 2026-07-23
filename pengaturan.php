<?php
// Pengaturan Umum
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-cog"></i> Pengaturan Sistem</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama Aplikasi:</strong> Sistem Absensi Santri Al-Halimi</p>
                    <p><strong>Versi:</strong> 1.0.0</p>
                    <p><strong>Versi PHP:</strong> <?= phpversion() ?></p>
                    <p><strong>Versi MySQL:</strong> <?php echo mysqli_get_server_info(); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Database</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> <span class="badge bg-success">Terhubung</span></p>
                    <p><strong>Nama Database:</strong> absensi_alhalimi</p>
                    <p><strong>Host:</strong> localhost</p>
                    <p><strong>Charset:</strong> UTF8MB4</p>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-warning">
        <strong><i class="fas fa-info-circle"></i> Informasi Penting:</strong>
        <ul class="mb-0 mt-2">
            <li>Hubungi administrator jika ada masalah dengan sistem</li>
            <li>Lakukan backup data secara berkala</li>
            <li>Ubah password admin secara berkala untuk keamanan</li>
            <li>Jangan bagikan akun admin kepada orang lain</li>
        </ul>
    </div>
</body>
</html>