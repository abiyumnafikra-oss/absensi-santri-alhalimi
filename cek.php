<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=empty");
        exit;
    }

    // Hash password dengan MD5
    $password_hash = md5($password);

    // Query untuk cek user
    $query = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Set session
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } elseif ($user['role'] === 'guru') {
            header("Location: dashboard_guru.php");
        } elseif ($user['role'] === 'siswa') {
            header("Location: dashboard_siswa.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        header("Location: index.php?error=invalid");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>