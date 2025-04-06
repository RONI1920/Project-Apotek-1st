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
                        <li><a href="Katalog-obat-keras.php">Obat Keras</a></li>
                        <li><a href="../pages/katalog-obat-sedang.php">Obat Sedang</a></li>
                        <li><a href="../pages/katalog-obat-keras.php">Obat Bebas</a></li>
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
                <a href="">
                    <img src="../icon/6928347.png" class="menu-icon" alt="Tentang" />
                    <span>Tentang Kami</span>
                </a>
            </li>
        </ul>

        <div class="logout-sidebar">
            <a href="../auth/logout.php" title="Logout">
                <img src="../icon/4033019.png" class="menu-icon" alt="Logout" />
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main>

        <!-- Header -->
        <header class="header">
            <div class="header-right">
                <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                <!-- Jika ingin sembunyikan foto, cukup hapus atau beri komentar di bawah -->
                <img src="../icon/pngtree-vector-users-icon-png-image_856952.jpg" alt="User Photo" class="user-photo">
            </div>
        </header>

        <!-- Konten Utama -->
        <section class="content">
        <h2><?= $greeting . ", " . htmlspecialchars($_SESSION['username']) ?></h2>
            <p>Silakan pilih kategori obat untuk melihat produk kami.</p>
            <div class="kategori-grid">
                <a href="katalog-obat-keras.php" class="kategori-card">
                    <img src="../icon/obat_keras_h7diak.png" alt="Obat Keras" />
                    <p>Obat Keras</p>
                </a>
                <a href="katalog-obat-bebas.php" class="kategori-card">
                    <img src="../icon/hipwee-terbatas.jpg" alt="Obat Sedang" />
                    <p>Obat Sedang</p>
                </a>
                <a href="katalog-obat-sedang.php" class="kategori-card">
                    <img src="../icon/logo-obat-bebas-doktersehat-300x300.png" alt="Obat Bebas" />
                    <p>Obat Bebas</p>
                </a>
            </div>
        </section>
    </main>
</div>

<script src="../script/script-dashbord.js"></script>
</body>
</html>
