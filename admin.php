<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $harga = filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_INT);
    $stok = filter_var($_POST['stok'], FILTER_SANITIZE_NUMBER_INT);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    // Cek apakah file dikirim
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = "images/";
        $nama_file = time() . '-' . basename($gambar); // nama file unik
        $target = $folder . $nama_file;

        // Cek semua input
        if (!empty($nama) && is_numeric($harga) && is_numeric($stok) && !empty($deskripsi)) {
            if (move_uploaded_file($tmp, $target)) {
                $stmt = $conn->prepare("INSERT INTO obat (nama, harga, stok, deskripsi, gambar) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiss", $nama, $harga, $stok, $deskripsi, $nama_file);
                $stmt->execute();

                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Upload gambar gagal!');</script>";
            }
        } else {
            echo "<script>alert('Semua kolom harus diisi!');</script>";
        }
    } else {
        echo "<script>alert('Gambar belum diupload atau error saat upload.');</script>";
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
    <form method="POST" enctype="multipart/form-data">
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
        <div class="form-group">
            <label>Upload Gambar:</label>
            <input type="file" name="gambar" accept="image/*" required>
        </div>
        <br>
        <button type="submit">Simpan</button>
    </form>
    </div>
    <br>    
    <a href="index.php">Kembali</a>
</body>
</html>
