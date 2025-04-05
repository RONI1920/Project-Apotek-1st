<?php
session_start();
include "../config/config.php";

$success = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi sederhana
    if (empty($username) || empty($password) || empty($konfirmasi)) {
        $error = "Semua kolom wajib diisi.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $query = "SELECT * FROM admin WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah terdaftar.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $insert = "INSERT INTO admin (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("ss", $username, $hashedPassword);
            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil. Silakan login.";
            } else {
                $error = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Admin</title>
    <link rel="stylesheet" href="../css/css-login.css">
</head>
<body>

<div class="login-container">
    <h2>Daftar Admin Apotek</h2>

    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo $success; ?></div>
        <a href="login.php">Kembali ke login</a>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required />
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required />
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" required />
            </div>
            <button type="submit">Daftar</button>
        </form>
        <div class="footer-text">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
