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
    <title>Section 3</title>
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
    <h2>Stationary</h2>
    <p>This is the stationary section of the website.</p>
    <a href="main.php">Back to Main Page</a> |
    <a href="logout.php">Logout</a>
</body>
</html>
