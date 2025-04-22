<?php

require_once('./template.header.php');
require_once('../models/BaseClass.php');

// Inisialisasi objek ProdukRepository dengan koneksi database
$produkRepo = new ProdukRepository($conn);

// Inisialisasi objek KeranjangService dengan ProdukRepository
$keranjangService = new KeranjangService($produkRepo);

// Inisialisasi controller
$produkController = new ProdukController($produkRepo, $keranjangService);

// Ambil kategori produk dari URL
$kategori = $_GET['kategori'] ?? null;

// Menangani permintaan produk
$produk = $produkController->handleRequest();

// Mengambil status dari session
$status = $produkController->get_status();

// Mengambil produk berdasarkan kategori atau semua produk
$vitamin = $produkRepo->get_all('vitamin'); // Menambahkan ini untuk menginisialisasi $alat
// Cek apakah status adalah 'success' atau 'updated'
if ($status == 'success'): ?>
    <div class="notifikasi-sukses">
        ✅ Produk berhasil dimasukkan ke keranjang!
    </div>
<?php
    // Hapus status dari sesi setelah ditampilkan
    $produkController->clear_status(); // Menghapus status agar tidak muncul setelah refresh
elseif ($status == 'updated'): ?>
    <div class="notifikasi-update">
        🔄 Produk yang sama sudah ada di keranjang. Jumlah diperbarui.
    </div>
<?php
    // Hapus status dari sesi setelah ditampilkan
    $produkController->clear_status(); // Menghapus status agar tidak muncul setelah refresh
endif;
?>

<h2 class="judul">Katalog Vitamin & Suplemen Lengkap</h2>

<div class="katalog-container">
    <?php foreach ($vitamin as $row): ?>
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