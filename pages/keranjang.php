<?php
require_once "../pages/template.header.php";

// Koneksi Database
$db = new Database();
$conn = $db->get_connect_to_data_base();

// Membuat objek produk repo dan keranjang service
$produkRepo = new ProdukRepository($conn);
$keranjangService = new KeranjangService($produkRepo);

// Membuat objek controller
$controller = new ProdukController($produkRepo, $keranjangService);

// Menangani request produk
$produk = $controller->handleRequest();

// Tampilkan produk atau kirim ke tampilan
foreach ($produk as $item) {
    // Output produk
}


// Jika tidak ada produk
header("Location: katalog-obat.php?status=failed");
exit;
?>
