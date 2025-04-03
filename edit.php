<?php
include "config.php";

// Ambil data obat berdasarkan ID
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM obat WHERE id=$id");
$row = mysqli_fetch_assoc($result);

// Jika form dikirim, update data di database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

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
    <link rel="stylesheet" href="css/styles.css">
    <title>Edit Obat</title>
</head>
<body>
    <h1>Edit Obat</h1>
    <div class="form-container">
    <form method="POST">
        <div class="form-group">
        <input type="text" name="nama" value="<?= $row['nama']; ?>" required>
        </div>
        <br>
        <div class="form-group">
        <input type="number" name="harga" value="<?= $row['harga']; ?>" required>
        </div>
        <br>
        <div class="form-group">
        <textarea name="deskripsi" required><?= $row['deskripsi']; ?></textarea>
        </div>
        <br>
        <button type="submit">Simpan Perubahan</button>
    </form>
    </div>
    <br>
    <a href="index.php">Kembali</a>
</body>
</html>
