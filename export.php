<?php
include 'config.php';

$kelasList = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");

$bulan_awal = $_GET['bulan_awal'] ?? date('m');
$tahun_awal = $_GET['tahun_awal'] ?? date('Y');
$bulan_akhir = $_GET['bulan_akhir'] ?? date('m');
$tahun_akhir = $_GET['tahun_akhir'] ?? date('Y');
$kelas = $_GET['kelas'] ?? '';
$action = $_GET['action'] ?? '';

if ($action == 'export') {
    $kelasNama = ($kelas != '') ? $kelas : "semua";
    $filename = "absensi_{$kelasNama}_{$bulan_awal}-{$tahun_awal}_sampai_{$bulan_akhir}-{$tahun_akhir}.xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $tanggal_awal  = date("Y-m-01", strtotime("$tahun_awal-$bulan_awal-01"));
    $tanggal_akhir = date("Y-m-t", strtotime("$tahun_akhir-$bulan_akhir-01"));

    $query = "SELECT a.tanggal, a.jam, a.status, a.keterangan, 
                     s.nis, s.nisn, s.nama, s.kelas
              FROM absensi a
              JOIN siswa s ON a.siswa_id = s.id
              WHERE a.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                AND s.status='aktif'";
    if ($kelas != '') {
        $query .= " AND s.kelas = '$kelas'";
    }
    $query .= " ORDER BY a.tanggal, s.nama";

    $result = mysqli_query($conn, $query);

    echo "Tanggal\tNIS\tNISN\tNama\tKelas\tJam\tStatus\tKeterangan\n";

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['tanggal'] . "\t" .
             $row['nis'] . "\t" .
             $row['nisn'] . "\t" .
             $row['nama'] . "\t" .
             $row['kelas'] . "\t" .
             $row['jam'] . "\t" .
             $row['status'] . "\t" .
             $row['keterangan'] . "\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Export Absensi ke Excel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2 class="text-center mb-4">📊 Export Absensi ke Excel</h2>
  <form method="get" class="card p-4" style="max-width: 600px; margin: auto;">
    <input type="hidden" name="action" value="export">

    <div class="mb-3">
      <label class="form-label">Kelas:</label>
      <select name="kelas" class="form-select">
        <option value="">Semua Kelas</option>
        <?php while ($k = mysqli_fetch_assoc($kelasList)): ?>
          <option value="<?= $k['kelas'] ?>" <?= ($k['kelas'] == $kelas) ? 'selected' : '' ?>><?= $k['kelas'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <fieldset class="mb-3">
      <legend class="form-label">Bulan Awal</legend>
      <select name="bulan_awal" class="form-select mb-2">
        <?php for ($b = 1; $b <= 12; $b++): ?>
          <option value="<?= $b ?>" <?= ($b == $bulan_awal) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $b, 10)) ?></option>
        <?php endfor; ?>
      </select>
      <input type="number" name="tahun_awal" class="form-control" value="<?= $tahun_awal ?>">
    </fieldset>

    <fieldset class="mb-3">
      <legend class="form-label">Bulan Akhir</legend>
      <select name="bulan_akhir" class="form-select mb-2">
        <?php for ($b = 1; $b <= 12; $b++): ?>
          <option value="<?= $b ?>" <?= ($b == $bulan_akhir) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $b, 10)) ?></option>
        <?php endfor; ?>
      </select>
      <input type="number" name="tahun_akhir" class="form-control" value="<?= $tahun_akhir ?>">
    </fieldset>

    <button type="submit" class="btn btn-success w-100 mb-2">📥 Export ke Excel</button>
    <a href="dashboard.php" class="btn btn-secondary w-100">← Kembali</a>
  </form>
</body>
</html>