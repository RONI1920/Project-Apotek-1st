<?php
include "config.php";

$id = $_GET['id'];
$sql = "DELETE FROM obat WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Obat berhasil dihapus!'); window.location.href='index.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
