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
    <title>Section 1</title>
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
    <img src="img_girl.jpg" alt="Girl in a jacket">
    <h2>Electornics</h2>
    <p>This is the electronics section of the website.</p>
    <a href="main.php">Back to Main Page</a> |
    <a href="logout.php">Logout</a>
<img src="img_girl.jpg" alt="Girl in a jacket">
</body>
</html>
