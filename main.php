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
        body {
            font-family: Arial, sans-serif; /* Ensure font consistency */
            margin: 0;
            padding: 0;
        }
        .navbar {
            overflow: hidden; /* Ensures the content is not overflowing */
            background-color: #333; /* Dark background for the navbar */
        }
        .navbar a {
            float: left; /* Align links to the left side */
            display: block; /* Make each link a block */
            color: #f2f2f2; /* Light color for text */
            text-align: center; /* Center text inside links */
            padding: 14px 16px; /* Padding inside each link */
            text-decoration: none; /* Remove underline from links */
            font-size: 30px;
        }
        .navbar a:hover {
            background-color: #ddd; /* Light background on hover */
            color: black; /* Dark text on hover */
        }
        h2 {
            color: black;
            display: flex;
            font-size: 36px;
            text-align: left;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-sizing: border-box;
        }
        h2 img {
            height: 60%;
            margin-left: 20px;
        }
        .center-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 40vh;
            font-size: 26px;
        }
        .center-text p {
            font-family: 'Georgia', serif;
        }
        .center-text img {
            margin-top: 10px;
            height: 300px;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="section1.php">Electronics</a>
        <a href="section2.php">Water Bottles</a>
        <a href="section3.php">Stationary</a>
        <a href="section4.php">Others</a>
        <a href="about.php">About</a>
        <a href="logout.php">Logout</a>
    </div>

    <h2>
        Welcome Students, This is the main page.
        <img src="img/inti_logo.png" alt="Inti Logo">
    </h2>

    <div class="center-text">
        <p>The website where you find your lost item in INTI Penang.</p>
        <img src="img/inti.jpg" alt="Inti">
    </div>
</body>
</html>
