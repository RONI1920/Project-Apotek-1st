<?php
include "config.php";
require_once __DIR__ . '/fpdf/fpdf.php';


$result = $conn->query("SELECT penjualan.*, obat.nama FROM penjualan JOIN obat ON penjualan.obat_id = obat.id ORDER BY penjualan.tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/styles.css">
    <title>Laporan Penjualan</title>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <table border="1">
        <tr>
            <th>Tanggal</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= $row['tanggal']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td>Rp. <?= number_format($row['total_harga']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Kembali</a>
</body>
</html>
