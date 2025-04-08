<?php
// sukses-ditambahkan.php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk Ditambahkan</title>
    <link rel="stylesheet" href="../css/notifikasi.css">
    <meta http-equiv="refresh" content="2;url=katalog-obat.php">
</head>
<body>

<div class="notifikasi-sukses">
    ✅ Produk berhasil dimasukkan ke keranjang!
</div>

<script>
    setTimeout(() => {
        const notif = document.querySelector('.notifikasi-sukses');
        if (notif) {
            notif.style.transition = 'opacity 0.5s';
            notif.style.opacity = '0';
        }
    }, 1500);
</script>

</body>
</html>
