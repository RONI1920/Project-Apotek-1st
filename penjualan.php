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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Form Penjualan</h2>
    <div class="form-container">
    <form action="proses_penjualan.php" method="POST" class="obat">
        <div class="form-group">
        <label>Pilih Obat:</label>
        <select name="obat_id" required>
            <?php while ($row = $query->fetch_assoc()) : ?>
                <option value="<?= $row['id']; ?>">
                    <?= $row['nama']; ?> - Stok: <?= $row['stok']; ?> - Rp. <?= number_format($row['harga']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        </div>
        <br>
        <div class="form-group">
        <label>Jumlah:</label>
        <input type="number" name="jumlah" required min="1">
        </div>
        <br>
        <div class="form-group">
        <button type="submit">Jual</button>
        </div>
    </form>
    </div>
    <a href="index.php">Kembali</a>
</body>
</html>
