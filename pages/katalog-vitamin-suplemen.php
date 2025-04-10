<?php
require_once 'template.header.php';
include("../config/config.php");

// Tangani penambahan ke keranjang
if (isset($_GET['add']) && isset($_GET['kategori'])) {
    $id_produk = (int) $_GET['add'];
    $kategori = $_GET['kategori']; // string

    // Ambil data produk berdasarkan ID dan KATEGORI
    $stmt = $conn->prepare("SELECT id, nama_produk, harga, gambar, stok, status FROM produk WHERE id = ? AND kategori = ?");
    $stmt->bind_param("is", $id_produk, $kategori); // <- Perbaiki di sini
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();

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

        header("Location: katalog-vitamin-suplemen.php?status=success");
        exit;
    } else {
        header("Location: katalog-vitamin-suplemen.php?status=failed");
        exit;
    }
}


// Ambil data semua produk dengan kategori vitamin
$query = $conn->prepare("SELECT * FROM produk WHERE kategori = 'vitamin' ORDER BY nama_produk ASC LIMIT 6");
$query->execute();
$result = $query->get_result();
?>

<h2 class="judul">Katalog Vitamin dan Suplemen</h2>

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
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="produk-card <?= $row['status'] == 'nonaktif' || $row['stok'] <= 0 ? 'sold-out' : '' ?>">
            <img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
            <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
            <p class="stok">Stok: <?= htmlspecialchars($row['stok']) ?> pcs</p>
            <?php if ($row['status'] == 'nonaktif' || $row['stok'] <= 0): ?>
                <button class="btn btn-disabled" disabled>❌ Sold Out</button>
            <?php else: ?>
                <a href="?add=<?= $row['id'] ?>&kategori=vitamin" class="btn">🛒 Masukkan Keranjang</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<div class="footer">
    <a href="index.php">← Kembali ke Beranda</a> |
    <a href="lihat-keranjang.php">🛒 Lihat Keranjang</a>
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
