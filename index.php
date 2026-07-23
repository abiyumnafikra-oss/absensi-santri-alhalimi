<?php
// Cek apakah sedang update (maintenance mode aktif)
if (file_exists(__DIR__ . "/maintenance.flag")) {
    die("<h1>Sedang update, silakan coba beberapa menit lagi...</h1>");
}
include "config.php";

// Ambil data profil sekolah
$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_sekolah, logo FROM profil_sekolah LIMIT 1"));
$nama_sekolah = $profil['nama_sekolah'] ?? 'Pondok Pesantren Al-Halimi';
$logo = $profil['logo'] ?? 'default.png';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Absensi Santri</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      flex-direction: column;
    }
    .login-container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 450px;
      animation: slideUp 0.5s ease-out;
    }
    .school-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .school-header img {
      max-height: 80px;
      display: block;
      margin: 0 auto 15px;
      border-radius: 50%;
    }
    .school-header h1 {
      font-size: 24px;
      color: #333;
      margin: 0 0 5px 0;
      font-weight: 700;
    }
    .school-header p {
      font-size: 13px;
      color: #666;
      margin: 0;
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-size: 20px;
      font-weight: 600;
    }
    .info-box {
      background-color: #f0f7ff;
      border-left: 5px solid #667eea;
      padding: 12px;
      margin-bottom: 20px;
      font-size: 13px;
      color: #333;
      border-radius: 5px;
      line-height: 1.5;
    }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      color: #333;
      font-size: 14px;
      font-weight: 500;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      transition: transform 0.2s ease;
      font-weight: 600;
    }
    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    button:active {
      transform: translateY(0);
    }
    .footer-links {
      margin-top: 25px;
      text-align: center;
      font-size: 13px;
      color: #666;
    }
    .footer-links a {
      color: #667eea;
      text-decoration: none;
      margin: 0 8px;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .footer-links a:hover {
      color: #764ba2;
      text-decoration: underline;
    }
    .app-version {
      margin-top: 15px;
      text-align: center;
      font-size: 12px;
      color: #999;
    }
    @media (max-width: 480px) {
      .login-container {
        padding: 25px;
        margin: 10px;
      }
      .school-header h1 {
        font-size: 20px;
      }
    }
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="school-header">
      <img src="uploads/<?php echo htmlspecialchars($logo); ?>" alt="Logo Pondok" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2250%22 fill=%22%23667eea%22/%3E%3C/svg%3E'">
      <h1><?php echo htmlspecialchars($nama_sekolah); ?></h1>
      <p>Sistem Absensi Santri</p>
    </div>

    <h2>🔐 Login</h2>

    <div class="info-box">
      <strong>📌 Akun Santri:</strong> Gunakan NISN sebagai username dan password
    </div>

    <form method="post" action="cek.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username atau NISN" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit">Masuk</button>
    </form>
  </div>

  <div class="footer-links">
    <a href="tentang.html">Tentang</a> | 
    <a href="#">Bantuan</a> |
    <a href="#">Kontak"></a>
    <div class="app-version">Versi 1.0.0 | Sistem Absensi Santri Al-Halimi</div>
  </div>
</body>
</html>