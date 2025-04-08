<?php
session_start();
include("../config/config.php");

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Cek apakah ada parameter add
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];

    // Ambil data produk
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($produk = $result->fetch_assoc()) {
        if (!isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => 1,
                'gambar' => $produk['gambar']
            ];
        } else {
            $_SESSION['keranjang'][$id]['jumlah']++;
        }

        // Redirect cepat dengan notifikasi
        header("Location: katalog-obat.php?status=success");
        exit;
    }
}

// Jika tidak ada produk
header("Location: katalog-obat.php?status=failed");
exit;
?>
