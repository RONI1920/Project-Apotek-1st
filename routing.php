<?php 
$url = isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : require_once 'index.php';   

if ($url == 'index'){
    require_once 'index.php';
} elseif ($url == 'obat') {
    require_once 'katalog-obat.php';
} elseif ($url == 'vitamin') {
    require_once 'katalog-vitamin-suplemen.php';
} elseif ($url == 'alat') {
    require_once 'katalog-alat-kesehatan.php';
} else {
    echo '404 URL Tidak ditemukan';
}

?>