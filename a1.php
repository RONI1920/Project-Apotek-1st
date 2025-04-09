<?php
session_start();
include('../config/config.php');

// Ambil isi keranjang dari session
$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];

$total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../css/css-lihat-keranjang.css">
</head>
<body>

<div class="report-container">

    <!-- Kop Surat Apotek -->
    <div class="letterhead">
        <h1>Apotek Sehat Sentosa</h1>
        <p>Jl. Kesehatan No. 123, Jakarta | Telp: (021) 12345678 | Email: info@apoteksehat.com</p>
    </div>

    <h2 style="text-align:center; margin-bottom: 25px;">🛒 Keranjang Belanja Anda</h2>

    <?php if (empty($keranjang)): ?>
        <div class="empty-cart">
            <p>Keranjang masih kosong.</p>
            <a href="../pages/index.php" class="btn-back">← Kembali ke Katalog</a>
        </div>
    <?php else: ?>
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

                        $qty = is_array($item) && isset($item['jumlah']) ? (int)$item['jumlah'] : (int)$item;
                        $pajak = $harga * $qty * 0.1;
                        $subtotal = $harga * $qty;
                        $total += $subtotal;
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
                                <span class="label">Tax 10 %:</span>
                                <span class="value">Rp <?= number_format($pajak, 0, ',', '.') ?></span>
                            </div>
                            <hr class="product-subtotal-separator">
                            <div class="product-subinfo subtotal">
                                <span class="label">Subtotal:</span>
                                <span class="value">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            </div>
                        </div>
            <?php
                    } else {
                        echo "<div class='product-item'><em>Produk ID $produk_id tidak ditemukan.</em></div><hr class='product-separator'>";
                    }
                }
            ?>
        </div>

        <!-- Ringkasan -->
        <div class="report-summary">
            <h4>Ringkasan Pembayaran</h4>
            <div class="summary-detail">
                <span>Total Pembayaran</span>
                <span class="total">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Tombol Bayar -->
        <form method="POST" action="qris-pembayaran.php" style="margin-top: 20px;">
            <input type="hidden" name="total" value="<?= $total ?>">
            <button type="submit" class="btn-bayar">💳 Bayar via QRIS</button>
        </form>

        <a href="pembayaran.php" class="btn" style="margin-top: 15px; display: inline-block;">← Pilih Metode Pembayaran Lain</a>
    <?php endif; ?>
</div>

</body>
</html>
