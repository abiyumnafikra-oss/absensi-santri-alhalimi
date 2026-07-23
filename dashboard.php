<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Jika role guru, arahkan ke dashboard guru
if ($_SESSION['role'] === 'guru') {
    header("Location: dashboard_guru.php");
    exit;
}

// Jika bukan admin, arahkan ke dashboard sesuai role
if ($_SESSION['role'] === 'siswa') {
    header("Location: dashboard_siswa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Sistem Absensi Santri</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    header h1 {
      font-size: 28px;
      margin-bottom: 5px;
    }
    header p {
      font-size: 14px;
      opacity: 0.9;
    }
    .container {
      flex: 1;
      padding: 30px 20px;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
      font-size: 24px;
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 30px;
    }
    .menu-item a {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: white;
      padding: 25px;
      border-radius: 12px;
      text-decoration: none;
      color: #333;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      border-top: 4px solid #667eea;
      min-height: 140px;
    }
    .menu-item a:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    .menu-item i {
      font-size: 28px;
      margin-bottom: 10px;
      color: #667eea;
    }
    .menu-item span {
      text-align: center;
      font-weight: 600;
      font-size: 14px;
      line-height: 1.3;
    }
    /* Warna khusus untuk kategori */
    .siswa i { color: #FF9800; }
    .siswa { border-top-color: #FF9800 !important; }
    
    .scan i { color: #4CAF50; }
    .scan { border-top-color: #4CAF50 !important; }
    
    .rekap i { color: #2196F3; }
    .rekap { border-top-color: #2196F3 !important; }
    
    .laporan i { color: #9C27B0; }
    .laporan { border-top-color: #9C27B0 !important; }
    
    .pengaturan i { color: #F44336; }
    .pengaturan { border-top-color: #F44336 !important; }
    
    .logout i { color: #FF5722; }
    .logout { border-top-color: #FF5722 !important; }
    
    footer {
      background: #333;
      color: white;
      text-align: center;
      padding: 20px;
      font-size: 13px;
    }
    @media (max-width: 768px) {
      .menu-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }
      header h1 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>📊 Dashboard Admin</h1>
    <p>Sistem Absensi Santri Al-Halimi</p>
  </header>

  <div class="container">
    <h2>Menu Utama</h2>
    
    <div class="menu-grid">
      <!-- Data Santri -->
      <div class="menu-item siswa">
        <a href="siswa.php">
          <i class="fas fa-user-graduate"></i>
          <span>Data Santri</span>
        </a>
      </div>

      <!-- Scan QR -->
      <div class="menu-item scan">
        <a href="scan.php">
          <i class="fas fa-qrcode"></i>
          <span>Scan QR Code</span>
        </a>
      </div>

      <!-- Input Absensi -->
      <div class="menu-item rekap">
        <a href="absensi.php">
          <i class="fas fa-clipboard-check"></i>
          <span>Input Absensi</span>
        </a>
      </div>

      <!-- Rekap Bulanan -->
      <div class="menu-item laporan">
        <a href="rekap_bulanan.php">
          <i class="fas fa-calendar-alt"></i>
          <span>Rekap Bulanan</span>
        </a>
      </div>

      <!-- Grafik -->
      <div class="menu-item laporan">
        <a href="grafik.php">
          <i class="fas fa-chart-line"></i>
          <span>Grafik Absensi</span>
        </a>
      </div>

      <!-- Export Excel -->
      <div class="menu-item laporan">
        <a href="export.php">
          <i class="fas fa-file-excel"></i>
          <span>Export Excel</span>
        </a>
      </div>

      <!-- Profil Pondok -->
      <div class="menu-item pengaturan">
        <a href="profil.php">
          <i class="fas fa-building"></i>
          <span>Profil Pondok</span>
        </a>
      </div>

      <!-- Pengaturan Jam -->
      <div class="menu-item pengaturan">
        <a href="jam_absensi.php">
          <i class="fas fa-clock"></i>
          <span>Pengaturan Jam</span>
        </a>
      </div>

      <!-- Hari Libur -->
      <div class="menu-item pengaturan">
        <a href="libur.php">
          <i class="fas fa-calendar-times"></i>
          <span>Hari Libur</span>
        </a>
      </div>

      <!-- Backup Database -->
      <div class="menu-item pengaturan">
        <a href="backup_restore.php">
          <i class="fas fa-database"></i>
          <span>Backup Database</span>
        </a>
      </div>

      <!-- Pengaturan -->
      <div class="menu-item pengaturan">
        <a href="pengaturan.php">
          <i class="fas fa-cog"></i>
          <span>Pengaturan</span>
        </a>
      </div>

      <!-- Logout -->
      <div class="menu-item logout">
        <a href="logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </div>

  <footer>
    © 2024 Sistem Absensi Santri Al-Halimi | Versi 1.0.0
  </footer>
</body>
</html>