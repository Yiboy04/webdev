<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: main.php");
    exit();
}

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple check: replace with a database query in real applications
    $valid_username = "Yi";
    $valid_password = "123";

    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: main.php");
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
    if (!empty($login_error)) {
        echo "<p style='color:red;'>$login_error</p>";
    }
    ?>
</body>
</html>
