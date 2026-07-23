<?php
session_start();
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'guru'])) {
    header("Location: index.php");
    exit;
}

include "config.php";
date_default_timezone_set("Asia/Jakarta");

if (isset($_GET['nisn'])) {
  $nisn = $_GET['nisn'];
  $tanggal = date("Y-m-d");
  $jam = date("H:i:s");

  $cekLibur = mysqli_query($conn, "SELECT * FROM hari_libur WHERE tanggal='$tanggal'");
  if (mysqli_num_rows($cekLibur) > 0) {
    echo "⛔ Hari ini libur!";
    exit;
  }

  $siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn='$nisn'");
  if (mysqli_num_rows($siswa) == 0) {
    echo "❌ Santri tidak ditemukan.";
    exit;
  }
  $s = mysqli_fetch_assoc($siswa);

  $cekAbsen = mysqli_query($conn, "SELECT * FROM absensi WHERE siswa_id={$s['id']} AND tanggal='$tanggal'");
  
  if (mysqli_num_rows($cekAbsen) == 0) {
    mysqli_query($conn, "INSERT INTO absensi (siswa_id, tanggal, jam, status) 
                         VALUES ({$s['id']}, '$tanggal', '$jam', 'H')");
    echo "✅ Absen berhasil: {$s['nama']} ({$s['kelas']})<br>🕒 Jam hadir: $jam";
  } else {
    $row = mysqli_fetch_assoc($cekAbsen);
    if (is_null($row['jam_pulang']) && $jam >= "12:00:00") {
      mysqli_query($conn, "UPDATE absensi SET jam_pulang='$jam' WHERE id={$row['id']}");
      echo "✅ Pulang berhasil: {$s['nama']} ({$s['kelas']})<br>🕒 Jam pulang: $jam";
    } else {
      echo "ℹ️ {$s['nama']} sudah absen hari ini.<br>🕒 Jam hadir: {$row['jam']}";
      if (!is_null($row['jam_pulang'])) {
        echo "<br>🕒 Jam pulang: {$row['jam_pulang']}";
      }
    }
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Scan QR Code</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    #reader { width: 100%; max-width: 500px; margin: 20px auto; }
    #result { background: #f9f9f9; padding: 15px; border-radius: 8px; max-height: 400px; overflow-y: auto; }
    .alert { margin-bottom: 10px; }
  </style>
</head>
<body class="container mt-5">
  <h2 class="text-center mb-4">📱 Scan QR Code Santri</h2>
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali</a>

  <div id="reader"></div>
  <div id="result"></div>

  <audio id="beepSound" src="beep.mp3" preload="auto"></audio>

  <script>
    function onScanSuccess(qrMessage) {
      fetch("scan.php?nisn=" + qrMessage)
        .then(res => res.text())
        .then(data => {
          let result = document.getElementById("result");
          let alertDiv = document.createElement("div");
          alertDiv.className = "alert alert-info";
          alertDiv.innerHTML = data;
          result.appendChild(alertDiv);
          document.getElementById("beepSound").play();
          result.scrollTop = result.scrollHeight;
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader",
      { fps: 10, qrbox: 250 },
      false
    );
    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>
</html>