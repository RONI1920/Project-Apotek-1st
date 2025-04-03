<?php
session_start();
include "config.php";
require_once __DIR__ . '/fpdf/fpdf.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Atur jumlah data per halaman
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total data
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM obat WHERE nama LIKE '%$search%'");
$total_result = mysqli_fetch_assoc($total_query);
$total_rows = $total_result['total'];
$total_pages = ceil($total_rows / $limit);

// Ambil data dengan limit & offset
$sql = "SELECT * FROM obat WHERE nama LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Sederhana</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>DAFTAR OBAT APOTEK BERKAH</h1>

    <form method="GET" class="form-container">
        <input type="text" name="search" placeholder="Cari obat..." value="<?= $search ?>">
        <button type="submit">Cari</button>
    </form>
    <br>

    <table>
    <tr>
        <th>Nama Obat</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <tr>
        <td><?= $row['nama']; ?></td>
        <td>Rp. <?= number_format($row['harga']); ?></td>
        <td><?= $row['stok']; ?></td>
        <td>
        <a href="penjualan.php">Penjualan</a> |
        <a href="stok.php?id=<?= $row['id']; ?>">Kelola Stok</a> |
        <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
        <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
    </td>

    </tr>
    <?php endwhile; ?>
</table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?= $page - 1; ?>&search=<?= $search ?>">Prev</a>
        <?php endif; ?>

        <span>Halaman <?= $page ?> dari <?= $total_pages ?></span>

        <?php if ($page < $total_pages) : ?>
            <a href="?page=<?= $page + 1; ?>&search=<?= $search ?>">Next</a>
        <?php endif; ?>
    </div>


    <div class="form-container">
    <a href="laporan.php" class="laporan">Laporan Penjualan</a>
    <a href="cetak_laporan.php" class="cetak" target="_blank">Cetak PDF</a>
    <a href="admin.php" class="tambah">Tambah Obat</a>
    </div>

    <?php if (isset($_SESSION['admin'])) : ?>
        <br><br>
    <?php endif; ?>
        <br>
    <a href="logout.php" class="logout">Logout</a>

</body>
</html>
