<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id && $status) {
        $query = "UPDATE produk SET status = '$status' WHERE id = $id";
        mysqli_query($conn, $query);
    }
    header('Location: admin-aksi.php');
    exit();
}
?>
