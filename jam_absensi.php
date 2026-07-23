<?php
// Jam Absensi Configuration
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
include "config.php";

$msg = "";

if (isset($_POST['simpan'])) {
    $jam_masuk = $_POST['jam_masuk'] ?? null;
    $jam_pulang = $_POST['jam_pulang'] ?? null;

    $conn->query("UPDATE profil_sekolah SET 
        jam_masuk = " . ($jam_masuk ? "'$jam_masuk'" : "NULL") . ",
        jam_pulang = " . ($jam_pulang ? "'$jam_pulang'" : "NULL") . "
    WHERE id=1");

    $msg = "<div class='alert alert-success'>✅ Jam absensi berhasil disimpan!</div>";
}

$profil = $conn->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Jam Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">🕒 Pengaturan Jam Absensi</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

    <?php echo $msg; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Jam Masuk (Contoh: 06:30)</label>
                    <input type="time" name="jam_masuk" class="form-control" value="<?= $profil['jam_masuk'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam Pulang (Contoh: 15:00)</label>
                    <input type="time" name="jam_pulang" class="form-control" value="<?= $profil['jam_pulang'] ?>">
                </div>
                <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>