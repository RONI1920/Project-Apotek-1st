<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: index.php");
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Login Admin</h1>
    <form method="POST">
        <div class="form-container">
            <div class="form-group">
        <input type="text" name="username" placeholder="Username" required>
        </div>
        <br>
        <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
        </div>
        <br>
        <button type="submit" class="a">Login</button>
    </form>
    </div>
</body>
</html>
