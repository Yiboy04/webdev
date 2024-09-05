<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <style>
        h2 {
            background-color: red;
            height: 20vh; 
            color: black;
            display: flex;
            font-size: 36px;
            text-align: left;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h2>Welcome Students, This is the main page.</h2>
    <p>This website can help you find your lost item in INTI Penang.</p>
    <a href="section1.php">Go to Electronics</a><br><br>
    <a href="section2.php">Go to Water Bottles</a><br><br>
    <a href="section3.php">Go to Stationary</a><br><br>
    <a href="logout.php">Logout</a>
</body>
</html>
