<?php
session_start();
if (empty($_SESSION['keranjang'])) {
    header("Location: katalog-obat-keras.php");
    exit;
}

// Hitung total
$total = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total += $item['harga'] * $item['jumlah'];
}

// Batas waktu pembayaran (misal 1 jam dari sekarang)
$expiredTime = date("H:i", strtotime("+1 hour"));
$tanggal = date("d-m-Y");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="css/css-pembayaran.css">
</head>
<body>
<div class="container">
    <h2>💳 Pembayaran</h2>

    <p><strong>Tanggal:</strong> <?= $tanggal ?></p>
    <p><strong>Total Tagihan:</strong><br>
        <span class="harga-total">
            Rp <?= number_format($total, 0, ',', '.') ?>
        </span>
    </p>

    <p><strong>Batas Waktu Pembayaran:</strong> Sebelum pukul <span class="expired-time"><?= $expiredTime ?></span></p>

    <hr>

    <p><strong>Metode Pembayaran:</strong></p>
    <ul>
        <li><strong>Transfer Bank BCA</strong><br>
            No. Rek: <strong>4499220</strong><br>
            a.n: <strong>Apotek Sehat</strong>
        </li>
        <li><strong>QRIS (semua e-wallet & mobile banking)</strong></li>
    </ul>

    <form action="qris-pembayaran.php" method="POST">
    <input type="hidden" name="total" value="<?= $total ?>">
    <button type="submit" class="btn-qris">🧾 Bayar via QRIS</button>
    </form>

    <p class="info-wa">
        📩 Setelah transfer, silakan kirim bukti pembayaran via WhatsApp ke: <br>
        <strong>0812-XXXX-XXXX</strong>
    </p>

    <a class="btn-back" href="katalog-obat-keras.php">&larr; Kembali Belanja</a>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> Apotek Sehat. All rights reserved.
</div>
</body>
</html>
