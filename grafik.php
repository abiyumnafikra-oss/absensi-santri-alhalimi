<?php
include 'config.php';

$kelas = $_GET['kelas'] ?? '';
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$kelasList = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");

$query = "SELECT a.tanggal, s.kelas, a.status
          FROM absensi a
          JOIN siswa s ON a.siswa_id = s.id
          WHERE MONTH(a.tanggal) = '$bulan' AND YEAR(a.tanggal) = '$tahun'";

if ($kelas != '') {
  $query .= " AND s.kelas = '$kelas'";
}

$result = mysqli_query($conn, $query);

$rekapGrafik = [];
$total = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
while ($row = mysqli_fetch_assoc($result)) {
    $tgl = date('d', strtotime($row['tanggal']));
    if (!isset($rekapGrafik[$tgl])) {
        $rekapGrafik[$tgl] = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
    }
    if (isset($rekapGrafik[$tgl][$row['status']])) {
        $rekapGrafik[$tgl][$row['status']]++;
        $total[$row['status']]++;
    }
}

$tanggalList = [];
$dataH = $dataS = $dataI = $dataA = [];

$jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
for ($i = 1; $i <= $jumlahHari; $i++) {
    $tglStr = str_pad($i, 2, '0', STR_PAD_LEFT);
    $tanggalList[] = $tglStr;
    $dataH[] = $rekapGrafik[$tglStr]['H'] ?? 0;
    $dataS[] = $rekapGrafik[$tglStr]['S'] ?? 0;
    $dataI[] = $rekapGrafik[$tglStr]['I'] ?? 0;
    $dataA[] = $rekapGrafik[$tglStr]['A'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Grafik Absensi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="container mt-5">
<h2 class="text-center mb-4">📈 Grafik Absensi Bulanan</h2>

<form method="get" class="row mb-4">
    <div class="col-md-3">
      <select name="kelas" class="form-select">
        <option value="">Semua Kelas</option>
        <?php mysqli_data_seek($kelasList, 0); while ($k = mysqli_fetch_assoc($kelasList)): ?>
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

<canvas id="grafikAbsensi" height="100"></canvas>

<script>
const ctx = document.getElementById('grafikAbsensi').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($tanggalList) ?>,
        datasets: [
            {
                label: 'Hadir (H)',
                data: <?= json_encode($dataH) ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0, 128, 0, 0.2)',
                fill: true
            },
            {
                label: 'Sakit (S)',
                data: <?= json_encode($dataS) ?>,
                borderColor: 'orange',
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                fill: true
            },
            {
                label: 'Izin (I)',
                data: <?= json_encode($dataI) ?>,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                fill: true
            },
            {
                label: 'Alpa (A)',
                data: <?= json_encode($dataA) ?>,
                borderColor: 'red',
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<div class="mt-4 p-3 bg-light rounded">
    <strong>Total Absensi:</strong>
    <span class="text-success">Hadir (H): <?= $total['H'] ?></span> | 
    <span class="text-warning">Sakit (S): <?= $total['S'] ?></span> | 
    <span class="text-info">Izin (I): <?= $total['I'] ?></span> | 
    <span class="text-danger">Alpa (A): <?= $total['A'] ?></span>
</div>

</body>
</html>