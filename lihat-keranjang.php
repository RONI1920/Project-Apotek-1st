<?php
session_start();
include("config/config.php");

// Tambah item ke keranjang jika ada parameter ?add=
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $query = $conn->query("SELECT * FROM obat WHERE id = $id");

    if ($query->num_rows > 0) {
        $item = $query->fetch_assoc();

        if (!isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id] = [
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'jumlah' => 1,
                'gambar' => $item['gambar']
            ];
        } else {
            $_SESSION['keranjang'][$id]['jumlah']++;
        }
    }
    header("Location: lihat-keranjang.php");
    exit;
}

// Hapus item
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    unset($_SESSION['keranjang'][$id]);
    header("Location: lihat-keranjang.php");
    exit;
}

// Update jumlah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['jumlah'] as $id => $jumlah) {
        if ($jumlah <= 0) {
            unset($_SESSION['keranjang'][$id]);
        } else {
            $_SESSION['keranjang'][$id]['jumlah'] = $jumlah;
        }
    }
    header("Location: lihat-keranjang.php");
    exit;
}

// Checkout (simulasi)
$pesan = "";
if (isset($_POST['checkout'])) {
    $_SESSION['keranjang'] = []; // kosongkan
    $pesan = "Terima kasih, pesanan Anda berhasil diproses!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="css/css-lihat-keranjang.css">
</head>
<body>
<h2 class="judul">🛒 Keranjang Belanja</h2>

<?php if (!empty($pesan)): ?>
    <p style="color: green; text-align:center;"><strong><?= $pesan ?></strong></p>
<?php endif; ?>

<?php if (empty($_SESSION['keranjang'])): ?>
    <div class="notifikasi-kosong">
        <strong>Oops!</strong> Keranjang Anda masih kosong. Yuk, pilih produk terlebih dahulu!
    </div>
    <div class="footer">
        <a href="../APOTEK_RONI/pages/katalog-obat-bebas.php">&larr; Kembali ke Katalog</a>
    </div>
<?php else: ?>
<form method="POST">
    <table>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
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
            <td><img src="images/<?= htmlspecialchars($item['gambar']) ?>" width="50"></td>
            <td><?= htmlspecialchars($item['nama']) ?></td>
            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td>
                <input type="number" name="jumlah[<?= $id ?>]" value="<?= $item['jumlah'] ?>" min="1">
            </td>
            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
            <td><a href="?hapus=<?= $id ?>" onclick="return confirm('Hapus item ini?')">Hapus</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4">Total Belanja</th>
            <th colspan="2">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
        </tr>
    </table>
    <div style="text-align:center">
        <button type="submit" name="update">📝 Update Jumlah</button>
        <a href="pembayaran.php" class="btn-checkout" onclick="return confirm('Lanjut ke pembayaran?')">✅ Checkout</a>
    </div>
</form>
<div class="footer" style="text-align:center; margin-top: 20px">
    <a href="katalog-obat-keras.php">&larr; Lanjut Belanja</a>
</div>
<?php endif; ?>
</body>
</html>