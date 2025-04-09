<?php
session_start();
include('../config/config.php');

// Pastikan transaksi sudah ada
if (!isset($_SESSION['transaksi_id'])) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$transaksi_id = $_SESSION['transaksi_id'];

// Jika form pembayaran dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = 'paid';

    // Update status pembayaran di transaksi
    $stmt = $conn->prepare("UPDATE transaksi SET status_pembayaran = ?, metode_pembayaran = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $metode_pembayaran, $transaksi_id);
    $stmt->execute();

    // Ambil produk dalam keranjang untuk transaksi ini
    $result = $conn->query("SELECT produk_id, jumlah FROM keranjang WHERE transaksi_id = $transaksi_id");
    while ($row = $result->fetch_assoc()) {
        $produk_id = $row['produk_id'];
        $jumlah = $row['jumlah'];

        // Kurangi stok produk
        $updateStok = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
        $updateStok->bind_param("ii", $jumlah, $produk_id);
        $updateStok->execute();

        // Catat di laporan penjualan
        $tanggal = date('Y-m-d');
        $laporan = $conn->prepare("INSERT INTO laporan_penjualan (produk_id, jumlah, tanggal) VALUES (?, ?, ?)");
        $laporan->bind_param("iis", $produk_id, $jumlah, $tanggal);
        $laporan->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <link rel="stylesheet" href="../css/css-pembayaran.css">
</head>
<body>

<div class="container">
    <h2>Pilih Metode Pembayaran</h2>
    <form method="POST">

        <div class="kategori-pembayaran">
            <h3>Transfer Bank</h3>
            <div class="opsi-pembayaran" onclick="setPaymentMethod('Transfer Bank BCA')">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3d/Bank_Logo.svg/1024px-Bank_Logo.svg.png" alt="Bank" width="40">
                <span>BCA</span>
            </div>
        </div>

        <div class="kategori-pembayaran">
            <h3>Virtual Account</h3>
            <div class="va-box">
                <label for="va-number">Nomor Virtual Account:</label>
                <div class="va-wrapper">
                    <input type="text" id="va-number" value="8808123412341234" readonly>
                    <button type="button" onclick="copyVA()">Salin</button>
                </div>
            </div>
        </div>

        <div class="kategori-pembayaran">
            <h3>Dompet Digital</h3>
            <div class="opsi-pembayaran" onclick="setPaymentMethod('OVO')">
                <img src="https://seeklogo.com/images/O/ovo-logo-F5E4D563A2-seeklogo.com.png" alt="OVO" width="40">
                <span>OVO</span>
            </div>
            <div class="opsi-pembayaran" onclick="setPaymentMethod('GoPay')">
                <img src="https://seeklogo.com/images/G/gopay-logo-7371B3A389-seeklogo.com.png" alt="GoPay" width="40">
                <span>GoPay</span>
            </div>
        </div>

        <div class="kategori-pembayaran">
        <a href="../pages/qris-pembayaran.php" title="Logout">QRIS</a>
        </a>
        </div>

        <div class="kategori-pembayaran">
            <h3>Cash / COD</h3>
            <div class="opsi-pembayaran" onclick="setPaymentMethod('COD')">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Car_icon.svg/1024px-Car_icon.svg.png" alt="COD" width="40">
                <span>Cash on Delivery</span>
            </div>
        </div>

        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>

        <button type="submit">Proses Pembayaran</button>
        <br><br>
        <a href="../pages/index.php" class="button-kembali">Kembali ke Menu Utama</a>
    </form>
</div>

<script>
    function setPaymentMethod(method) {
        document.getElementById('metode_pembayaran').value = method;
    }

    function copyVA() {
        const vaInput = document.getElementById("va-number");
        vaInput.select();
        vaInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Nomor VA disalin: " + vaInput.value);
    }

    function redirectQRIS() {
        setPaymentMethod('QRIS');
        sessionStorage.setItem('metode_pembayaran', 'QRIS');
        window.location.href = '../pages/qris-pembayaran.php';
    }
</script>

</body>
</html>