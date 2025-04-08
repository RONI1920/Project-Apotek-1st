<?php
session_start();
include("../config/config.php");

// Ambil kategori dari parameter GET
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// Jika kategori kosong, tampilkan pesan
if (empty($kategori)) {
    echo "<p style='color:red; text-align:center;'>Kategori tidak ditemukan.</p>";
    exit;
}

// Tangani penambahan ke keranjang
if (isset($_GET['add'])) {
    $id_produk = (int) $_GET['add'];

    // Ambil data produk berdasarkan ID dan KATEGORI
    $stmt = $conn->prepare("SELECT id, nama_produk, harga, gambar, stok, status FROM produk WHERE id = ? AND kategori = ?");
    $stmt->bind_param("is", $id_produk, $kategori);
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

        header("Location: katalog-alat-kesehatan.php?kategori=$kategori&status=success");
        exit;
    } else {
        header("Location: katalog-alat-kesehatan.php?kategori=$kategori&status=failed");
        exit;
    }
}

// Ambil data produk dari database berdasarkan kategori
$query = $conn->prepare("SELECT * FROM produk WHERE kategori = ? ORDER BY nama_produk ASC LIMIT 6");
$query->bind_param("s", $kategori);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog <?= htmlspecialchars(ucwords(str_replace("-", " ", $kategori))) ?> - Apotek Sehat</title>
    <link rel="stylesheet" href="../css/css-katalog.css">
    <link rel="stylesheet" href="../css/notifikasi.css">
</head>
<body>

<h2 class="judul">Katalog: <?= htmlspecialchars(ucwords(str_replace("-", " ", $kategori))) ?></h2>

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
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="produk-card <?= $row['status'] == 'nonaktif' || $row['stok'] <= 0 ? 'sold-out' : '' ?>">
                <img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
                <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                <p class="stok">Stok: <?= htmlspecialchars($row['stok']) ?> pcs</p>
                <?php if ($row['status'] == 'nonaktif' || $row['stok'] <= 0): ?>
                    <button class="btn btn-disabled" disabled>❌ Sold Out</button>
                <?php else: ?>
                    <a href="?kategori=<?= urlencode($kategori) ?>&add=<?= $row['id'] ?>" class="btn">🛒 Masukkan Keranjang</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:gray;">Tidak ada produk dalam kategori ini.</p>
    <?php endif; ?>
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

</body>
</html>
