<?php
// Atur untuk halaman aksi
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total data dari tabel `produk`
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE '%$search%'");
$total_result = mysqli_fetch_assoc($total_query);
$total_rows = $total_result['total'];
$total_pages = ceil($total_rows / $limit);

// Ambil data dengan limit & offset dari tabel `produk`
$sql = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

?>