<?php
require_once "../pages/template.header.php";

// Cek apakah parameter id tersedia dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = (int) $_GET['id'];

// Ambil data obat berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM obat WHERE id = $id");
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Obat tidak ditemukan.";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Jika form dikirim, update data di database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int) $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $sql = "UPDATE obat SET nama='$nama', harga='$harga', deskripsi='$deskripsi' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Obat berhasil diperbarui!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
        }
        button {
            padding: 8px 16px;
        }
    </style>
</head>
<body>

    <h1 style="text-align:center;">✏️ Edit Obat</h1>
    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label>Nama Obat</label><br>
                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
            </div>
            <br>
            <div class="form-group">
                <label>Harga</label><br>
                <input type="number" name="harga" value="<?= $row['harga']; ?>" required>
            </div>
            <br>
            <div class="form-group">
                <label>Deskripsi</label><br>
                <textarea name="deskripsi" required><?= htmlspecialchars($row['deskripsi']); ?></textarea>
            </div>
            <br>
            <button type="submit">💾 Simpan Perubahan</button>
        </form>
        <br>
        <a href="index.php">← Kembali ke Daftar Obat</a>
    </div>

</body>
</html>
