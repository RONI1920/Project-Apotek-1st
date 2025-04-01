<?php
session_start();
session_destroy();
header("Location: login.php"); // Arahkan ke halaman login atau index
exit;
?>
