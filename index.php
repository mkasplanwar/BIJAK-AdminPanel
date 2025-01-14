<?php
session_start();

// // Database connection
// $host = 'localhost';
// $username = 'root';
// $password = '';
// $database = 'db_bijak';

// $conn = mysqli_connect($host, $username, $password, $database);

// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// Menyertakan file koneksi
require 'connection.php';

// Proses login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIJAK Admin - Login</title>
    <link rel="stylesheet" href="src/css/index.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            BIJAK<span class="admin-tag">ADMIN</span>
        </div>
    </div>
    <div class="main-content">
        <div class="login-container">
            <h1>Log in</h1>
            <p class="subtitle">Welcome back! Please enter username: admin, password: admin123 to access dashboard</p>
            <?php if(isset($error)) { ?>
            <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>
            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>
                
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password"  required placeholder="Enter your password">
                    </div>
                </div>
                
                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember">
                        Keep me signed in on this device
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
    <script src="src/js/index.js"></script>
</body>
</html>