<?php
require_once('./template.header.php');
require_once "../models/BaseClass.php";


//inisiasi objek
$produklist = new ProdukRepository();

// Menangkap query pencarian
$search = isset($_GET['search']) ? $_GET['search'] : ''; 
// Jumlah produk per halama
$limit = 10; 
 // Halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// Menentukan offset untuk query pagination
$offset = ($page - 1) * $limit; 
// Ambil total produk untuk pagination
$produkTotal = $produklist->get_total_produk($search);
// Hitung jumlah total halaman
$total_pages = ceil($produkTotal / $limit);
// Ambil produk sesuai dengan pagination dan pencarian
$result = $produklist->get_total_pagination($limit, $offset, $search);
?>

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

    <?php if (count($result) > 0): ?>
        <?php foreach ($result as $row): ?>
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
                <a href="../pages/edit.php?id=<?= $row['id']; ?>">Edit</a> |
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
        <?php endforeach; ?>
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

<?php require_once "template.footer.php" ?>
