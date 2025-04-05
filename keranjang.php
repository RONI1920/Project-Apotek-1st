<?php
session_start();
$id = $_GET['id'] ?? null;

if ($id) {
    // Tambahkan ke sesi keranjang
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Jika produk sudah ada, tambahkan jumlah
    if (isset($_SESSION['keranjang'][$id])) {
        $_SESSION['keranjang'][$id] += 1;
    } else {
        $_SESSION['keranjang'][$id] = 1;
    }

    header("Location: katalog.php"); // redirect kembali ke katalog
    exit();
}
?>
