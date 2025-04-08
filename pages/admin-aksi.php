<?php
session_start();
include('../config/config.php');
require_once __DIR__ . '/../fpdf/fpdf.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Atur jumlah data per halaman
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total data dari tabel `produk`
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE '%$search%'");
$total_result = mysqli_fetch_assoc($total_query);
$total_rows = $total_result['total'];
$total_pages = ceil($total_rows / $limit);

// Ambil data dengan limit & offset dari tabel `produk`
$sql = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Sederhana</title>
    <link rel="stylesheet" href="../css/css-admin-aksi.css">
</head>
<body>
    <h1>DAFTAR PRODUK APOTEK BERKAH</h1>

    <form method="GET" class="form-container">
        <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>
    <br>

    <table>
        <tr>
            <th>ID</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Deskripsi</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Aksi</th>
            <th>Sell</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td>
                    <?php if (!empty($row['gambar'])): ?>
                    <img src="../images/<?= htmlspecialchars($row['gambar']); ?>" width="50">
                    <?php else: ?>
                        Tidak ada gambar
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                <td>Rp. <?= number_format($row['harga']); ?></td>
                <td><?= htmlspecialchars($row['kategori']); ?></td>
                <td><?= $row['stok']; ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
                    <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                </td>
                <td>
    <form method="POST" action="toggle-status.php">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <input type="hidden" name="status" value="<?= $row['status'] == 'aktif' ? 'nonaktif' : 'aktif' ?>">
        <label class="switch">
            <input type="checkbox" onchange="this.form.submit()" <?= $row['status'] == 'aktif' ? 'checked' : '' ?>>
            <span class="slider round"></span>
        </label>
    </form>
    <?php if ($row['status'] == 'nonaktif'): ?>
        <div style="color:red; font-weight:bold;">Sold Out</div>
    <?php endif; ?>
</td>

            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center;">Data tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?= $page - 1; ?>&search=<?= urlencode($search) ?>">Prev</a>
        <?php endif; ?>

        <span>Halaman <?= $page ?> dari <?= $total_pages ?></span>

        <?php if ($page < $total_pages) : ?>
            <a href="?page=<?= $page + 1; ?>&search=<?= urlencode($search) ?>">Next</a>
        <?php endif; ?>
    </div>

    <div class="menu-actions">
    <a href="../pages/index.php" class="katalog">Katalog Produk</a>
    <a href="laporan.php" class="laporan">Laporan Penjualan</a>
    <a href="../pages/tambah-produk.php" class="tambah">Tambah Produk</a>
    <a href="cetak_laporan.php" class="cetak" target="_blank">Cetak PDF</a>
    </div>

    <?php if (isset($_SESSION['admin'])) : ?>
        <br><br>
    <?php endif; ?>
    <a href="../pages/index.php">← Kembali ke Menu</a>

</body>
</html>
