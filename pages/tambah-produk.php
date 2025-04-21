<?php
require_once "../pages/template.header.php";
include("../config/config.php");

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
                    <option value="vitamin">Vitamin dan Suplemen</option>
                    <option value="alat">Alat Kesehatan</option>
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
