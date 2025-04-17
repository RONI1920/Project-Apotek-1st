<?php

require_once('./template.header.php');
require_once 'class.kategori.php';

$vitamin = new Katalog($conn, 'vitamin'); // koneksi dikirim
$data_vitamin = $vitamin->getAll(); // ambil data dari database

?>

<h2 class="judul">Katalog Vitamin & Suplemen Lengkap</h2>

<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="notifikasi-sukses">
        ✅ Produk berhasil dimasukkan ke keranjang!
    </div>
<?php endif; ?>

<div class="katalog-container">
    <?php foreach ($data_vitamin as $row): ?>
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
    <?php endforeach; ?>
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
