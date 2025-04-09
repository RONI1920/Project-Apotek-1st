<?php
session_start();
include('../config/config.php');

// Redirect jika keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    header("Location: ../pages/index.php");
    exit;
}

// Proses update jumlah jika tombol update ditekan
if (isset($_POST['update'])) {
    foreach ($_POST['jumlah'] as $id => $jumlah) {
        $id = (int) $id;
        $jumlah = (int) $jumlah;

        if ($jumlah <= 0) {
            unset($_SESSION['keranjang'][$id]);
        } else {
            if (isset($_SESSION['keranjang'][$id])) {
                $_SESSION['keranjang'][$id]['jumlah'] = $jumlah;
            }
        }
    }
    header("Location: lihat-keranjang.php");
    exit;
}

// Hanya lanjutkan jika tombol checkout ditekan
if (!isset($_POST['checkout'])) {
    header("Location: ../pages/index.php");
    exit;
}

$keranjang = $_SESSION['keranjang'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Tagihan</title>
    <link rel="stylesheet" href="../css/css-preview-bill.css">
</head>
<body>

<div class="report-container">

    <!-- Kop Surat Apotek -->
    <div class="letterhead">
        <h1>Apotek Sehat Sentosa</h1>
        <p>Jl. Kesehatan No. 123, Jakarta | Telp: (021) 12345678 | Email: info@apoteksehat.com</p>
    </div>

    <h2 style="text-align:center; margin-bottom: 25px;">🧾 Preview Tagihan Belanja Anda</h2>

    <div class="product-list">
        <?php 
            foreach ($keranjang as $produk_id => $item) {
                $query = $conn->prepare("SELECT nama_produk, harga FROM produk WHERE id = ?");
                $query->bind_param("i", $produk_id);
                $query->execute();
                $result = $query->get_result();
                $produk = $result->fetch_assoc();

                if ($produk) {
                    $nama = htmlspecialchars($produk['nama_produk']);
                    $harga = (int)$produk['harga'];

                    $qty = isset($item['jumlah']) ? (int)$item['jumlah'] : 1;
                    $subtotal = $harga * $qty;
                    $pajak = $subtotal * 0.10;
                    $totalItem = $subtotal + $pajak;
                    $total += $totalItem;
        ?>
            <div class="product-item">
                <div class="product-line">
                    🧴 <strong><?= $nama ?></strong>
                    <span class="product-qty">x <?= $qty ?></span>
                </div>
                <hr class="product-separator">
                <div class="product-subinfo">
                    <span class="label">Harga:</span>
                    <span class="value">Rp <?= number_format($harga, 0, ',', '.') ?></span>
                </div>
                <div class="product-subinfo">
                    <span class="label">Subtotal:</span>
                    <span class="value">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                </div>
                <div class="product-subinfo">
                    <span class="label">Pajak (10%):</span>
                    <span class="value">Rp <?= number_format($pajak, 0, ',', '.') ?></span>
                </div>
                <hr class="product-subtotal-separator">
                <div class="product-subinfo subtotal">
                    <span class="label">Total Produk:</span>
                    <span class="value">Rp <?= number_format($totalItem, 0, ',', '.') ?></span>
                </div>
            </div>
        <?php
                } else {
                    echo "<div class='product-item'><em>Produk ID $produk_id tidak ditemukan.</em></div><hr class='product-separator'>";
                }
            }
        ?>
    </div>

    <!-- Ringkasan Total -->
    <div class="report-summary">
        <h4>Ringkasan Pembayaran</h4>
        <div class="summary-detail">
            <span>Total Pembayaran (termasuk pajak)</span>
            <span class="total">Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Tombol Bayar -->
    <form method="POST" action="qris-pembayaran.php" style="margin-top: 20px;">
        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit" class="btn-bayar">💳 Bayar via QRIS</button>
    </form>

    <!-- Pilihan Metode Lain -->
    <a href="pembayaran.php" class="btn" style="margin-top: 15px; display: inline-block;">← Pilih Metode Pembayaran Lain</a>
</div>

</body>
</html>
