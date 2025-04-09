<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['total'])) {
    header("Location: kwitansi.php");
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
<form method="POST" action="kwitansi.php">
    <input type="hidden" name="total" value="<?= $total ?>">
    <button type="submit" class="btn-bayar">✅ Saya Sudah Bayar</button>
</form>

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

<script>
    let countdown = 300; // 5 menit (300 detik)
    const timerDisplay = document.createElement('p');
    timerDisplay.style.fontSize = '16px';
    timerDisplay.style.fontWeight = 'bold';
    timerDisplay.style.color = '#333';
    document.querySelector('.container').appendChild(timerDisplay);

    function updateTimer() {
        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        timerDisplay.textContent = `⏳ Waktu tersisa untuk pembayaran: ${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = "../pages/index.php"; // redirect jika waktu habis
        }

        countdown--;
    }

    const interval = setInterval(updateTimer, 1000);
    updateTimer(); // jalankan langsung sekali di awal
</script>


</body>
</html>
