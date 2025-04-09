<?php
session_start();
include('../config/config.php');

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    unset($_SESSION['keranjang'][$id]);
    header("Location: lihat-keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../css/css-preview-keranjang.css">
</head>
<body>

<h2 class="judul">🛒 Keranjang Belanja Anda</h2>

<?php if (empty($_SESSION['keranjang'])): ?>
    <div class="notifikasi-kosong">
        <strong>Keranjang kosong.</strong> Silakan kembali ke katalog untuk berbelanja.
    </div>
    <div class="footer">
        <a href="katalog-obat.php">← Kembali ke Katalog</a>
    </div>
<?php else: ?>
    <!-- Form gabungan untuk update dan checkout -->
    <form method="POST" action="preview-tagihan.php">
        <table border="1" cellpadding="8" cellspacing="0" style="margin:auto;">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
            <?php
            $grandTotal = 0;
            foreach ($_SESSION['keranjang'] as $id => $item):
                $total = $item['harga'] * $item['jumlah'];
                $grandTotal += $total;
            ?>
            <tr>
                <td><img src="../images/<?= htmlspecialchars($item['gambar']) ?>" width="50"></td>
                <td><?= htmlspecialchars($item['nama']) ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><input type="number" name="jumlah[<?= $id ?>]" value="<?= $item['jumlah'] ?>" min="1"></td>
                <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                <td><a href="?hapus=<?= $id ?>" onclick="return confirm('Hapus item ini?')">🗑 Hapus</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="4">Total Belanja</th>
                <th colspan="2">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
            </tr>
        </table>

        <div style="text-align:center; margin-top: 15px;">
            <button type="submit" name="update">🔄 Update Jumlah</button>
            <button type="submit" name="checkout" onclick="return confirm('Yakin ingin checkout?')">✅ Checkout</button>
        </div>
    </form>

    <div class="footer" style="text-align:center; margin-top:20px;">
        <a href="../pages/index.php">← Lanjut Belanja</a>
    </div>
<?php endif; ?>

</body>
</html>
