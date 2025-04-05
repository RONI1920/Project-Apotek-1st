<?php
session_start();
include "../config/config.php"; // koneksi ke database

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data admin berdasarkan username
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Cek password menggunakan password_verify
        if (password_verify($password, $admin['password'])) {
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];

            header("Location: ../pages/index.php");
            exit;
        } else {
            $error = "password";
        }
    } else {
        $error = "username";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin</title>
    <link rel="stylesheet" href="../css/css-login.css">
</head>
<body>

<div class="login-container">
    <?php if (isset($_GET['timeout']) && $_GET['timeout'] == "true"): ?>
        <div style="color: red; margin-bottom: 10px;">Sesi Anda telah berakhir. Silakan login kembali.</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div style="color: red; margin-bottom: 10px;">
            <?php
                if ($error === 'username') echo "Username tidak ditemukan.";
                elseif ($error === 'password') echo "Password salah.";
            ?>
        </div>
    <?php endif; ?>

    <h2>Login Admin Apotek</h2>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required />
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="footer-text">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
    </div>
</div>

</body>
</html>
