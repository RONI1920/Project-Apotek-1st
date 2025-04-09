<?php

require_once('./template.header.php');
include("../config/config.php");

$result = $conn->query("SELECT * FROM produk WHERE kategori = 'obat' LIMIT 10");

if (!$result) {
    die("Gagal mengambil data produk: " . $conn->error);
}

?>

<h2 class="judul">Katalog Obat Lengkap & Terpercaya</h2>

<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="notifikasi-sukses">
        ✅ Produk berhasil dimasukkan ke keranjang!
    </div>
<?php endif; ?>

<div class="katalog-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="produk-card <?= $row['status'] == 'nonaktif' || $row['stok'] <= 0 ? 'sold-out' : '' ?>">
    <img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
    <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
    <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
    <p class="stok">Stok: <?= htmlspecialchars($row['stok']) ?> pcs</p>
    <?php if ($row['status'] == 'nonaktif' || $row['stok'] <= 0): ?>
        <button class="btn btn-disabled" disabled>❌ Sold Out</button>
    <?php else: ?>
        <a href="keranjang.php?add=<?= $row['id'] ?>" class="btn">🛒 Masukkan Keranjang</a>
    <?php endif; ?>
</div>

    <?php endwhile; ?>
</div>

<div class="footer">
    <a href="index.php">← Kembali ke Beranda</a> | 
    <a href="preview-keranjang.php">🛒 Lihat Keranjang</a>
</div>

<script>
    setTimeout(() => {
        const notif = document.querySelector('.notifikasi-sukses');
        if (notif) {
            notif.style.transition = 'opacity 1s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 1000);
        }
    }, 5000); // muncul selama 5 detik
</script>

