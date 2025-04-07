<?php
session_start();
include('config/config.php');

// Ambil data produk berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conn->prepare("SELECT * FROM produk WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $nama_produk = $result->fetch_assoc();

    if (!$nama_produk) {
        echo "<script>alert('Data produk tidak ditemukan!'); window.location.href='../APOTEK_RONI/pages/index.php';</script>";
        exit();
    }
}

// Proses update stok dan kategori
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $stok_baru = intval($_POST['stok_baru']);
    $kategori_baru = trim($_POST['kategori']);

    if ($stok_baru >= 0) {
        $stmt = $conn->prepare("UPDATE produk SET stok = ?, kategori = ? WHERE id = ?");
        $stmt->bind_param("isi", $stok_baru, $kategori_baru, $id);
        $stmt->execute();
        $success = true;

        // Reload data setelah update
        $query = $conn->prepare("SELECT * FROM produk WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        $nama_produk = $result->fetch_assoc();
    } else {
        $error = "Stok tidak boleh kurang dari 0!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Stok Produk</title>
    <link rel="stylesheet" href="../APOTEK_RONI/css/css-stok.css">
</head>
<body>
    <div class="container-wrapper">
        <h2>Kelola Stok: <?= htmlspecialchars($nama_produk['nama']) ?></h2>

        <div class="form-container">
            <?php if (isset($success)): ?>
                <div class="alert success">✅ Stok & Kategori berhasil diperbarui!</div>
            <?php elseif (isset($error)): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="obat-info">
                <img src="../APOTEK_RONI/uploads/<?= htmlspecialchars($nama_produk['gambar']) ?>" alt="<?= htmlspecialchars($nama_produk['nama']) ?>">
                <div class="detail">
                    <p><strong>Nama Produk:</strong> <?= htmlspecialchars($nama_produk['nama']) ?></p>
                    <p><strong>Stok Saat Ini:</strong> <?= $nama_produk['stok']; ?> pcs</p>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($nama_produk['kategori']) ?></p>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $nama_produk['id']; ?>">

                <div class="form-group">
                    <label>Ubah Stok:</label>
                    <input type="number" name="stok_baru" value="<?= $nama_produk['stok']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori:</label>
                    <select name="kategori" required>
                        <option value="Obat" <?= $nama_produk['kategori'] === 'Obat' ? 'selected' : '' ?>>Obat</option>
                        <option value="Vitamin" <?= $nama_produk['kategori'] === 'Vitamin' ? 'selected' : '' ?>>Vitamin</option>
                        <option value="Alat" <?= $nama_produk['kategori'] === 'Alat' ? 'selected' : '' ?>>Alat</option>
                    </select>
                </div>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>

        <a href="../APOTEK_RONI/pages/index.php">← Kembali ke Beranda</a>
    </div>
</body>
</html>
