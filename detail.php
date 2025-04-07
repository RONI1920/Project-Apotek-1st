<?php
session_start();
include("config/config.php");

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil detail obat berdasarkan ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID obat tidak ditemukan.");
}

$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Obat tidak ditemukan.");
}

$obat = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Obat - <?= htmlspecialchars($obat['nama']) ?></title>
    <link rel="stylesheet" href="css/css-detail.css">
</head>
<body>

<h2 class="judul">Detail Obat</h2>

<div class="container">
    <div class="image">
        <img src="images/<?= htmlspecialchars($obat['gambar']) ?>" alt="<?= htmlspecialchars($obat['nama_produk']) ?>">
    </div>
    <div class="info">
        <h1><?= htmlspecialchars($obat['nama_produk']) ?></h1>
        <p class="harga">Rp <?= number_format($obat['harga'], 0, ',', '.') ?></p>
        <p class="stok">Stok: <?= htmlspecialchars($obat['stok']) ?> pcs</p>
        <p class="deskripsi"><?= nl2br(htmlspecialchars($obat['deskripsi'] ?? '')) ?></p>
        <div class="actions">
            <a href="lihat-keranjang.php?add=<?= $obat['id'] ?>" class="btn tambah">+ Tambahkan ke Keranjang</a>
            <a href="pages/katalog-obat1.php" class="btn kembali">← Kembali</a>
        </div>
    </div>
</div>

</body>
</html>
