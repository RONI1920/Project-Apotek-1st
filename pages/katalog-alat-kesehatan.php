<?php
require_once 'template.header.php';
require_once ('../models/class.kategori.php');

// Membuat objek dari kelas katalog dan memanggil kategori 'alat'
$alat = new Katalog(); // 
$data_alat = $alat->get_all('alat'); // 

// Tangani penambahan ke keranjang
if (isset($_GET['add']) && isset($_GET['kategori'])) {
    $id_produk = (int) $_GET['add'];
    $kategori = $_GET['kategori']; // string

    if ($produk && $produk['status'] == 'aktif' && $produk['stok'] > 0) {
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        if (isset($_SESSION['keranjang'][$id_produk])) {
            $_SESSION['keranjang'][$id_produk]['jumlah'] += 1;
        } else {
            $_SESSION['keranjang'][$id_produk] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'gambar' => $produk['gambar'],
                'jumlah' => 1
            ];
        }

        header("Location: katalog-alat-kesehatan.php?status=success");
        exit;
    } else {
        header("Location: katalog-alat-kesehatan.php?status=failed");
        exit;
    }
}



?>

<h2 class="judul">Katalog Alat Kesehatan</h2>

<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="notifikasi-sukses">
        ✅ Produk berhasil dimasukkan ke keranjang!
    </div>
<?php elseif (isset($_GET['status']) && $_GET['status'] == 'failed'): ?>
    <div class="notifikasi-gagal">
        ❌ Gagal menambahkan produk. Produk tidak tersedia.
    </div>
<?php endif; ?>

<div class="katalog-container">
<?php foreach ($alat->get_all('alat') as $row): ?>
    <div class="produk-card <?= $row['status'] == 'nonaktif' || $row['stok'] <= 0 ? 'sold-out' : '' ?>">
            <img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
            <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
            <p class="stok">Stok: <?= htmlspecialchars($row['stok']) ?> pcs</p>
            <?php if ($row['status'] == 'nonaktif' || $row['stok'] <= 0): ?>
                <button class="btn btn-disabled" disabled>❌ Sold Out</button>
            <?php else: ?>
                <a href="?add=<?= $row['id'] ?>&kategori=alat" class="btn">🛒 Masukkan Keranjang</a>
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
        const notif = document.querySelector('.notifikasi-sukses') || document.querySelector('.notifikasi-gagal');
        if (notif) {
            notif.style.transition = 'opacity 1s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 1000);
        }
    }, 5000);
</script>

<?php require_once 'template.footer.php' ?>
