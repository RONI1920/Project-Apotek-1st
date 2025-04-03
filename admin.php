<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $harga = filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_INT);
    $stok = filter_var($_POST['stok'], FILTER_SANITIZE_NUMBER_INT);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    // Cek apakah semua input diisi
    if (!empty($nama) && is_numeric($harga) && is_numeric($stok) && !empty($deskripsi)) {
        $stmt = $conn->prepare("INSERT INTO obat (nama, harga, stok, deskripsi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $nama, $harga, $stok, $deskripsi);
        $stmt->execute();
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Obat</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Tambah Obat</h2>
    <div class="form-container">
    <form method="POST">
        <div class="form-group">
        <label>Nama Obat:</label>
        <input type="text" name="nama" required>
        </div>
        <br>
        <div class="form-group">
        <label>Harga:</label>
        <input type="number" name="harga" required>
        </div>
        <br>
        <div class="form-group">
        <label>Stok:</label>
        <input type="number" name="stok" required>
        </div>
        <br>
        <div class="form-group">
        <label>Deskripsi:</label>
        <textarea name="deskripsi"></textarea>
        </div>
        <br>
        <button type="submit">Simpan</button>
    </form>
    </div>
    <a href="index.php">Kembali</a>
</body>
</html>
