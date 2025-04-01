<?php
$host = "localhost";
$user = "root";
$pass = "192022";
$db = "apotek_roni";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

session_start();
if (isset($_SESSION['admin']) && time() - $_SESSION['last_login'] > 1800) { // 30 menit
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_login'] = time();


?>

