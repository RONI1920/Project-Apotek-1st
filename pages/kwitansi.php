<?php
session_start();
include('../config/config.php');

// Pastikan total tersedia
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['total'])) {
    header("Location: ../pages/index.php");
    exit;
}

$total_input = (int)$_POST['total'];
$keranjang = $_SESSION['keranjang'] ?? [];

$transaksi_id = 'TRX' . time();
$tanggal = date('Y-m-d H:i:s');

// Buat variabel kalkulasi awal
$sub_total = 0;
$diskon = 0;
$pajak = 0;
$keuntungan = 0;

// Loop isi keranjang
foreach ($keranjang as $produk_id => $item) {
    $jumlah = is_array($item) ? $item['jumlah'] : (int) $item;

    // Ambil data produk
    $query = $conn->prepare("SELECT nama_produk, harga, stok, harga_modal FROM produk WHERE id = ?");
    $query->bind_param("i", $produk_id);
    $query->execute();
    $result = $query->get_result();
    $produk = $result->fetch_assoc();

    if (!$produk) continue;

    $nama_produk = $produk['nama_produk'];
    $harga = $produk['harga'];
    $stok = $produk['stok'];
    $harga_modal = $produk['harga_modal'];

    $total_harga = $harga * $jumlah;
    $laba_produk = ($harga - $harga_modal) * $jumlah;

    // Update subtotal & keuntungan
    $sub_total += $total_harga;
    $keuntungan += $laba_produk;

    // Update stok
    $stok_baru = $stok - $jumlah;
    $update_stok = $conn->prepare("UPDATE produk SET stok = ? WHERE id = ?");
    $update_stok->bind_param("ii", $stok_baru, $produk_id);
    $update_stok->execute();

    // Simpan per item ke laporan
    $insert = $conn->prepare("INSERT INTO pembayaran (transaksi_id, nama_produk, jumlah, harga_satuan, total_harga, tanggal)
        VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssiids", $transaksi_id, $nama_produk, $jumlah, $harga, $total_harga, $tanggal);
    $insert->execute();
}


// Hitung diskon dan pajak setelah subtotal
$diskon = $sub_total * 0.10;
$setelah_diskon = $sub_total - $diskon;
$pajak = $setelah_diskon * 0.11;
$total_bayar = $setelah_diskon + $pajak;

// Kosongkan keranjang
unset($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran</title>
    <link rel="stylesheet" href="../css/css-kwitansi.css">
</head>
<body>
<div class="kwitansi-container">
    <div class="header">
        <h1>Apotek Sehat Sentosa</h1>
        <p>Jl. Sehat Selalu No. 10, Indonesia | Telp: 0812-XXXX-XXXX</p>
        <hr>
    </div>

    <h2>🧾 Kwitansi Pembayaran</h2>

    <p><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaksi_id) ?></p>
    <p><strong>Tanggal:</strong> <?= date("d/m/Y H:i") ?></p>
    <p><strong>Subtotal:</strong> Rp <?= number_format($sub_total, 0, ',', '.') ?></p>
    <p><strong>Diskon (10%):</strong> Rp <?= number_format($diskon, 0, ',', '.') ?></p>
    <p><strong>Pajak (11%):</strong> Rp <?= number_format($pajak, 0, ',', '.') ?></p>
    <p><strong>Total Dibayar:</strong> <span class="harga">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span></p>

    <div class="thanks">
        ✅ <strong>Terima kasih</strong> atas pembayaran Anda.<br>
        Pesanan Anda akan segera kami proses.
    </div>

    <a href="../pages/index.php" class="btn-kembali">← Kembali ke Katalog</a>
</div>
</body>
</html>