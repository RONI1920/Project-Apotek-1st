<?php
include("../config/config.php");

$kategori = 'obat';

// Gunakan prepared statement
$stmt = $conn->prepare("SELECT * FROM produk WHERE kategori = ?");
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Apotek Sehat - Katalog Obat Lengkap</title>
    <link rel="stylesheet" href="../css/css-katalog.css">
</head>
<body>

<h2 class="judul">Katalog Obat Lengkap & Terpercaya</h2>    

<div class="katalog-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="produk-card">
            <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
            <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
        </div>
    <?php endwhile; ?>
</div>

<div class="footer">
    <a href="../pages/index.php">← Kembali ke Menu</a> |
    <a href="../lihat-keranjang.php">🛒 Lihat Keranjang</a>
</div>


</body>
</html>
