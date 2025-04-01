<?php
include "config.php";

// Ambil daftar obat dari database
$query = $conn->query("SELECT * FROM obat WHERE stok > 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Penjualan</title>
</head>
<body>
    <h2>Form Penjualan</h2>
    <form action="proses_penjualan.php" method="POST" class="obat">
        <label>Pilih Obat:</label>
        <select name="obat_id" required>
            <?php while ($row = $query->fetch_assoc()) : ?>
                <option value="<?= $row['id']; ?>">
                    <?= $row['nama']; ?> - Stok: <?= $row['stok']; ?> - Rp. <?= number_format($row['harga']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br>
        <label>Jumlah:</label>
        <input type="number" name="jumlah" required min="1">
        <br>
        <button type="submit">Jual</button>
    </form>
    <a href="index.php">Kembali</a>
</body>
</html>
