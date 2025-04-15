<?php
// Cek apakah parameter URL kosong
if (!isset($_GET['url']) || empty($_GET['url'])) {
    $url = 'index';
} else {
    $url = $_GET['url'];
}

// Routing berdasarkan URL
if ($url == 'index') {
    require_once 'dashbord.php';
} elseif ($url == 'obat') {
    require_once 'katalog-obat.php';
} elseif ($url == 'vitamin') {
    require_once 'katalog-vitamin-suplemen.php';
} elseif ($url == 'alat') {
    require_once 'katalog-alat-kesehatan.php';
} else {
    echo '404 - URL Tidak ditemukan';
}
?>
