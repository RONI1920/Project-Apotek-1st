<?php
session_start();
include("../config/config.php");

// Cek apakah koneksi sukses
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$result = $conn->query("SELECT * FROM produk");

if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Apotek Sehat - Katalog Obat Lengkap</title>
    <link rel="stylesheet" href="../css/css-katalog.css">
</head>
<body>

<h2 class="judul">Katalog Obat</h2>    

<div class="katalog-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="produk-card">
            <img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
            <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
            <p class="stok">Stok: <?= htmlspecialchars($row['stok']) ?> pcs</p>

            <a href="../detail.php?id=<?= urlencode($row['id']) ?>" class="btn">Lihat Detail</a>
            <a href="../lihat-keranjang.php?add=<?= urlencode($row['id']) ?>" class="btn tambah">+ Tambahkan ke Keranjang</a>
        </div>
    <?php endwhile; ?>
</div>

<div class="footer">
    <a href="../pages/index.php">← Kembali ke Menu</a> |
    <a href="../lihat-keranjang.php">🛒 Lihat Keranjang</a>
</div>


</body>
</html>
