<?php
include "config.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $obat_id = intval($_POST['obat_id']);
    $jumlah = intval($_POST['jumlah']);

    // Ambil data obat
    $query = $conn->prepare("SELECT stok, harga FROM obat WHERE id = ?");
    $query->bind_param("i", $obat_id);
    $query->execute();
    $result = $query->get_result();
    $obat = $result->fetch_assoc();

    if ($obat && $jumlah > 0 && $jumlah <= $obat['stok']) {
        $total_harga = $jumlah * $obat['harga'];

        // Kurangi stok obat
        $stmt = $conn->prepare("UPDATE obat SET stok = stok - ? WHERE id = ?");
        $stmt->bind_param("ii", $jumlah, $obat_id);
        $stmt->execute();

        // Simpan transaksi ke tabel penjualan
        $stmt = $conn->prepare("INSERT INTO penjualan (obat_id, jumlah, total_harga) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $obat_id, $jumlah, $total_harga);
        $stmt->execute();

        echo "<script>alert('Transaksi berhasil!'); window.location.href='laporan.php';</script>";
    } else {
        echo "<script>alert('Stok tidak mencukupi atau jumlah tidak valid!'); history.back();</script>";
    }
}
?>
