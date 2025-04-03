<?php
include "config.php";

// Ambil ID obat dari URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conn->prepare("SELECT * FROM obat WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $obat = $result->fetch_assoc();
}

// Update stok jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $stok_baru = intval($_POST['stok_baru']);

    if ($stok_baru >= 0) {
        $stmt = $conn->prepare("UPDATE obat SET stok = ? WHERE id = ?");
        $stmt->bind_param("ii", $stok_baru, $id);
        $stmt->execute();
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Stok tidak boleh kurang dari 0!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Stok Obat</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Kelola Stok: <?= $obat['nama']; ?></h2>
    <div class="form-container">
    <form method="POST">
        <div class="form-group">
        <input type="hidden" name="id" value="<?= $obat['id']; ?>">
        <label>Stok Saat Ini:</label>
        <input type="number" value="<?= $obat['stok']; ?>" readonly>
        </div>
        <br>
        <div class="form-group">
        <label>Ubah Stok:</label>
        <input type="number" name="stok_baru" value="<?= $obat['stok']; ?>" required>
        </div>
        <br>
        <button type="submit">Simpan</button>
    </form>
    </div>
    <a href="index.php">Kembali</a>
</body>
</html>
