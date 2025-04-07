<?php
include('config/config.php');


$id = $_GET['id'];
$sql = "DELETE FROM produk WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Obat berhasil dihapus!'); window.location.href='../APOTEK_RONI/pages/index.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
