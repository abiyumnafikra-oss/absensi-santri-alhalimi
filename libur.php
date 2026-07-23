<?php
// Hari Libur Management
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
include "config.php";

$msg = "";

// Tambah hari libur
if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    $conn->query("INSERT INTO hari_libur (tanggal, deskripsi) VALUES ('$tanggal', '$deskripsi')");
    $msg = "<div class='alert alert-success'>✅ Hari libur ditambahkan!</div>";
}

// Hapus hari libur
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM hari_libur WHERE id=$id");
    header("Location: libur.php");
}

$hasil = $conn->query("SELECT * FROM hari_libur ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Hari Libur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">📅 Manajemen Hari Libur</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

    <?php echo $msg; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Hari Libur</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi (Contoh: Hari Raya, Cuti Bersama)</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Masukkan keterangan libur">
                </div>
                <button type="submit" name="tambah" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Daftar Hari Libur</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $hasil->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                        <td><?= $row['deskripsi'] ?></td>
                        <td>
                            <a href="libur.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>