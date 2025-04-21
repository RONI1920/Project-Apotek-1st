<?php
session_start();
include("../config/config.php");
include ("../models/BaseClass.php");

// Inisialisasi dan jalankan
$keranjang = new ProdukRepository($conn);

// Jika tidak ada produk
header("Location: katalog-obat.php?status=failed");
exit;
?>
