<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telepon = htmlspecialchars($_POST['no_telepon']);

    // Cek apakah username sudah ada
    $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan'); window.location.href='register-form.php';</script>";
        exit;
    }

    // Simpan data
    $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, no_telepon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $username, $password, $telepon);

    if ($stmt->execute()) {
        echo "<script>
                alert('Pendaftaran berhasil!');
                window.location.href = 'index.php';
            </script>";
    } else {
        echo "<script>alert('Gagal mendaftar. Coba lagi.');</script>";
    }
}
?>
