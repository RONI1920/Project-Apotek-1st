<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['total'])) {
    header("Location: pembayaran.php");
    exit;
}

$total = $_POST['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QRIS Pembayaran</title>
    <link rel="stylesheet" href="../css/css-qris-pembayaran.css">
</head>
<body>
<div class="container">
    <h2>🔍 Scan QR untuk Bayar</h2>
    
    <p><strong>Total Pembayaran:</strong></p>
    <p class="harga">Rp <?= number_format($total, 0, ',', '.') ?></p>
    
    <div class="qris-box">
        <img src="../images/6.-pajang-dan-print-qris-toko-new.png" alt="QRIS" class="qris-img">
        <p class="petunjuk">Scan menggunakan aplikasi e-wallet atau mobile banking</p>
    </div>

    <p class="konfirmasi">📩 Setelah pembayaran, kirim bukti ke WhatsApp: <strong>0812-XXXX-XXXX</strong></p>

    <a href="../pages/index.php" class="btn-back">← Kembali ke Katalog</a>
</div>
</body>
</html>
