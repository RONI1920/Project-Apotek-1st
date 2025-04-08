<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/form-login.php?timeout=true");
    exit;
}

date_default_timezone_set('Asia/Jakarta'); // pastikan zona waktu benar
$hour = date("H");

if ($hour >= 5 && $hour < 11) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 11 && $hour < 15) {
    $greeting = "Selamat Siang";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat Sore";
} else {
    $greeting = "Selamat Malam";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Apotek Sehat</title>
    <link rel="stylesheet" href="../css/css-index.css" />
</head>
<body>
<div class="container">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../images/caduceus-600nw-29636281.webp" alt="Logo Apotek">
        </div>
        <ul class="menu">
            <li>
                <a href="">
                    <img src="../icon/Home_36756.webp" class="menu-icon" alt="Home" />
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <div class="menu-dropdown">
                    <a href="#">
                        <img src="../icon/3639167.png" class="menu-icon" alt="Produk" />
                        <span>Produk</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="../pages/katalog-obat.php">Obat</a></li>
                        <li><a href="../pages/katalog-vitamin-suplemen.php">Vitamin</a></li>
                        <li><a href="../pages/katalog-alat-kesehatan.php">Alat Medis</a></li>
                        <li><a href="../pages/tambah-produk.php">Tambah Produk</a></li>
                    </ul>
                </div>
            </li>   
            <li>
                <a href="">
                    <img src="../icon/9710991.png" class="menu-icon" alt="Kategori" />
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="../pages/admin-aksi.php">
                    <img src="../icon/3685367.png" class="menu-icon" alt="Kategori" />
                    <span>Menu Admin</span>
                </a>
            </li>
            <li>
                <a href="">
                    <img src="../icon/6928347.png" class="menu-icon" alt="Tentang" />
                    <span>Tentang Kami</span>
                </a>
            </li>
        </ul>

    </aside>

    <!-- Main Content -->
    <main>

        <!-- Header -->
        <header class="header">
    <div class="header-right">
        <div class="user-dropdown">
            <div class="user-info">
                <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                <img src="../icon/pngtree-vector-users-icon-png-image_856952.jpg" alt="User Photo" class="user-photo">
            </div>
            <ul class="header-submenu">
                <li><a href="#">Profil Saya</a></li>
                <li><a href="#">Pengaturan</a></li>
                <li><a href="../auth/logout.php" title="Logout">Keluar</a></li>
            </ul>
        </div>
    </div>
</header>


        <!-- Konten Utama -->
        <section class="content">
        <h2><?= $greeting . ", " . htmlspecialchars($_SESSION['username']) ?></h2>
            <p>Silakan pilih kategori obat untuk melihat produk kami.</p>
            <div class="kategori-grid">
                <a href="katalog-obat.php" class="kategori-card">
                    <img src="../icon/images.png" alt="Obat Keras" />
                    <p>Obat-obatan</p>
                </a>
                <a href="katalog-vitamin-suplemen.php" class="kategori-card">
                    <img src="../icon/8885328.png" alt="Obat Sedang" />
                    <p>Vitamin dan Suplemen</p>
                </a>
                <a href="katalog-alat-kesehatan.php" class="kategori-card">
                    <img src="../icon/stethoscope_doctor_health_medical_healthcare_icon_133555.webp" alt="Obat Bebas" />
                    <p>Alat Kesehatan</p>
                </a>
            </div>
        </section>
    </main>
</div>

<script src="../script/script-dashbord.js"></script>
</body>
</html>
