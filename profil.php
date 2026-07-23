<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
include "config.php";

$msg_profil = "";
$msg_password = "";

// Ambil data profil
$q = $conn->query("SELECT * FROM profil_sekolah LIMIT 1");
$profil = $q->fetch_assoc();

// Proses update profil
if (isset($_POST['simpan'])) {
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $kepala     = $_POST['kepala'];
    $nip        = $_POST['nip'];
    $jam_masuk  = $_POST['jam_masuk'];
    $jam_pulang = $_POST['jam_pulang'];

    // Upload logo jika ada
    $logo = $profil['logo'];
    if (!empty($_FILES['logo']['name'])) {
        $allowed_ext  = ['jpg','jpeg','png'];
        $file_tmp  = $_FILES['logo']['tmp_name'];
        $file_name = $_FILES['logo']['name'];
        $file_size = $_FILES['logo']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $msg_profil = "<span style='color:red;'>Format logo tidak diizinkan!</span>";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $msg_profil = "<span style='color:red;'>Ukuran logo maksimal 2MB!</span>";
        } else {
            $logo = "logo_" . time() . "." . $ext;
            move_uploaded_file($file_tmp, "uploads/" . $logo);
        }
    }

    $conn->query("UPDATE profil_sekolah SET 
        nama_sekolah     = '$nama',
        alamat           = '$alamat',
        kepala_sekolah   = '$kepala',
        nip_kepala       = '$nip',
        logo             = '$logo',
        jam_masuk        = " . ($jam_masuk ? "'$jam_masuk'" : "NULL") . ",
        jam_pulang       = " . ($jam_pulang ? "'$jam_pulang'" : "NULL") . "
    WHERE id=" . $profil['id']);

    if (!$msg_profil) {
        $msg_profil = "<span style='color:green;'>✅ Profil pondok berhasil diperbarui!</span>";
    }

    $q = $conn->query("SELECT * FROM profil_sekolah LIMIT 1");
    $profil = $q->fetch_assoc();
}

// Proses ubah password admin
if (isset($_POST['ubah_password'])) {
    $old     = $_POST['old_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $qAdmin = $conn->query("SELECT * FROM users WHERE username='admin' LIMIT 1");
    $admin  = $qAdmin->fetch_assoc();

    if (!$admin) {
        $msg_password = "<span style='color:red;'>Data admin tidak ditemukan!</span>";
    } elseif ($admin['password'] !== md5($old)) {
        $msg_password = "<span style='color:red;'>Password lama salah!</span>";
    } elseif ($new !== $confirm) {
        $msg_password = "<span style='color:red;'>Password baru dan konfirmasi tidak cocok!</span>";
    } else {
        $hash = md5($new);
        $conn->query("UPDATE users SET password='$hash' WHERE username='admin'");
        $msg_password = "<span style='color:green;'>✅ Password berhasil diubah!</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pondok Pesantren</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        .msg { margin: 15px 0; padding: 12px; border-radius: 5px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; color: #333; font-weight: 600; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus, textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        button { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; }
        button:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        img { max-width: 150px; margin: 10px 0; border-radius: 8px; }
        a { display: inline-block; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        a:hover { background: #5a6268; }
        hr { margin: 30px 0; border: none; border-top: 2px solid #e0e0e0; }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

    <h2><i class="fas fa-building"></i> Profil Pondok Pesantren</h2>
    <?php if ($msg_profil) echo "<div class='msg'>$msg_profil</div>"; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Pondok Pesantren</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($profil['nama_sekolah']) ?>" required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" rows="3" required><?= htmlspecialchars($profil['alamat']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Nama Kepala/Pimpinan</label>
            <input type="text" name="kepala" value="<?= htmlspecialchars($profil['kepala_sekolah']) ?>" required>
        </div>

        <div class="form-group">
            <label>NIP/NIK Pimpinan</label>
            <input type="text" name="nip" value="<?= htmlspecialchars($profil['nip_kepala']) ?>" required>
        </div>

        <div class="form-group">
            <label>Jam Masuk (Contoh: 06:30)</label>
            <input type="time" name="jam_masuk" value="<?= htmlspecialchars($profil['jam_masuk']) ?>" step="60">
        </div>

        <div class="form-group">
            <label>Jam Pulang (Contoh: 15:00)</label>
            <input type="time" name="jam_pulang" value="<?= htmlspecialchars($profil['jam_pulang']) ?>" step="60">
        </div>

        <div class="form-group">
            <label>Logo Pondok (PNG, JPG - Max 2MB)</label>
            <input type="file" name="logo" accept="image/*">
            <?php if ($profil['logo'] && file_exists("uploads/" . $profil['logo'])): ?>
                <img src="uploads/<?= $profil['logo'] ?>?v=<?= time() ?>" alt="Logo">
            <?php endif; ?>
        </div>

        <button type="submit" name="simpan"><i class="fas fa-save"></i> Simpan Profil</button>
    </form>

    <hr>

    <h2><i class="fas fa-lock"></i> Ubah Password Admin</h2>
    <?php if ($msg_password) echo "<div class='msg'>$msg_password</div>"; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password" required>
        </div>

        <div class="form-group">
            <label>Password Baru (gunakan kombinasi huruf & angka)</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="form-group">
            <label>Ulangi Password Baru</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" name="ubah_password"><i class="fas fa-key"></i> Ubah Password</button>
    </form>
</div>
</body>
</html>