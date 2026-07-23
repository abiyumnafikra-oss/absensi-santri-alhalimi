<?php
include 'config.php';

$kelas = $_GET['kelas'] ?? '';
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$kelasList = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");
$jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

$siswaQuery = "SELECT * FROM siswa WHERE status='aktif'";
if ($kelas != '') {
  $siswaQuery .= " AND kelas = '$kelas'";
}
$siswaQuery .= " ORDER BY nama";
$siswaResult = mysqli_query($conn, $siswaQuery);

$absensi = [];
$absensiQuery = "SELECT a.*, s.nis, s.nama FROM absensi a 
                 JOIN siswa s ON a.siswa_id = s.id 
                 WHERE MONTH(a.tanggal) = '$bulan' 
                   AND YEAR(a.tanggal) = '$tahun'
                   AND s.status='aktif'";
if ($kelas != '') {
  $absensiQuery .= " AND s.kelas = '$kelas'";
}
$resultAbsensi = mysqli_query($conn, $absensiQuery);

while ($row = mysqli_fetch_assoc($resultAbsensi)) {
  $sid = $row['siswa_id'];
  $tgl = (int)date('j', strtotime($row['tanggal']));
  $absensi[$sid][$tgl] = $row['status'];
}

$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kepala_sekolah, nip_kepala FROM profil_sekolah LIMIT 1"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekap Absensi Bulanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 20px; background: #f5f5f5; }
    table { background: white; font-size: 13px; }
    th, td { border: 1px solid #ddd; text-align: center; padding: 5px; }
    thead { background: #667eea; color: white; }
    .minggu { color: red; font-weight: bold; }
    .alpa { color: red; font-weight: bold; }
  </style>
</head>
<body>
  <div class="container-fluid">
    <h2 class="text-center mb-4">📋 Rekap Absensi Bulanan</h2>

    <form method="get" class="row mb-4">
      <div class="col-md-3">
        <select name="kelas" class="form-select">
          <option value="">Semua Kelas</option>
          <?php while ($k = mysqli_fetch_assoc($kelasList)): ?>
            <option value="<?= $k['kelas'] ?>" <?= ($k['kelas'] == $kelas) ? 'selected' : '' ?>><?= $k['kelas'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="bulan" class="form-select">
          <?php for ($b = 1; $b <= 12; $b++): ?>
            <option value="<?= $b ?>" <?= ($b == $bulan) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $b, 10)) ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>">
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <?php for ($i = 1; $i <= $jumlahHari; $i++): ?>
              <th><?= $i ?></th>
            <?php endfor; ?>
            <th>H</th><th>S</th><th>I</th><th>A</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($siswa = mysqli_fetch_assoc($siswaResult)) {
            $sid = $siswa['id'];
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$siswa['nis']}</td>";
            echo "<td>{$siswa['nama']}</td>";

            $countH = $countS = $countI = $countA = 0;

            for ($i = 1; $i <= $jumlahHari; $i++) {
              $val = $absensi[$sid][$i] ?? '';
              if ($val == 'H') {
                echo "<td>●</td>";
                $countH++;
              } elseif ($val == 'S') {
                echo "<td>S</td>";
                $countS++;
              } elseif ($val == 'I') {
                echo "<td>I</td>";
                $countI++;
              } elseif ($val == 'A') {
                echo "<td class='alpa'>A</td>";
                $countA++;
              } else {
                echo "<td></td>";
              }
            }

            echo "<td>$countH</td><td>$countS</td><td>$countI</td><td>$countA</td>";
            echo "</tr>";
            $no++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>