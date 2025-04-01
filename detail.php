<?php
include "config.php";

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM obat WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Detail Obat</title>
</head>
<body>
    <h1><?= $row['nama']; ?></h1>
    <p>Harga: Rp. <?= number_format($row['harga']); ?></p>
    <p><?= $row['deskripsi']; ?></p>
    <a href="index.php">Kembali</a>
</body>
</html>
