<?php
include("../config/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama       = htmlspecialchars($_POST['nama']);
    $kategori   = htmlspecialchars($_POST['kategori']);
    $harga      = filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_INT);
    $stok       = filter_var($_POST['stok'], FILTER_SANITIZE_NUMBER_INT);
    $deskripsi  = htmlspecialchars($_POST['deskripsi']);

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $nama_file = time() . '-' . rand() . '-' . basename($_FILES['gambar']['name']);
        $tmp       = $_FILES['gambar']['tmp_name'];
        $folder    = "../images/";

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $target = $folder . $nama_file;

        if (!empty($nama) && !empty($kategori) && is_numeric($harga) && is_numeric($stok) && !empty($deskripsi)) {
            if (move_uploaded_file($tmp, $target)) {
                $stmt = $conn->prepare("INSERT INTO produk (nama_produk, kategori, harga, stok, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiiss", $nama, $kategori, $harga, $stok, $deskripsi, $nama_file);

                if ($stmt->execute()) {
                    header("Location: ../pages/index.php");
                    exit();
                } else {
                    echo "<script>alert('Gagal menyimpan data ke database.');</script>";
                }
            } else {
                echo "<script>alert('Upload gambar gagal.');</script>";
            }
        } else {
            echo "<script>alert('Semua kolom harus diisi dengan benar.');</script>";
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
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../css/css-admin.css">
</head>
<body>
    <h2>Tambah Produk</h2>
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk:</label>
                <input type="text" name="nama" required>
            </div><br>

            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="obat">Obat</option>
                    <option value="alat_kesehatan">Alat Kesehatan</option>
                    <option value="suplemen">Suplemen</option>
                    <!-- Tambah kategori lain jika perlu -->
                </select>
            </div><br>

            <div class="form-group">
                <label>Harga:</label>
                <input type="number" name="harga" required>
            </div><br>

            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" required>
            </div><br>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="deskripsi" required></textarea>
            </div><br>

            <div class="form-group">
                <label>Upload Gambar:</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div><br>

            <button type="submit">Simpan</button>
        </form>
    </div>
    <br>
    <a href="index.php">Kembali</a>
</body>
</html>
