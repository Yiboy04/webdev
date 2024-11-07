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
    <title>Section 2</title>
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
            font-size: 25px;
        }
        .navbar a:hover {
            background-color: #ddd; /* Light background on hover */
            color: black; /* Dark text on hover */
        }
        h2 {
            background-color: white;
            height: 10vh; 
            color: black;
            display: flex;
            font-size: 50px;
            text-align: left;
            margin: 0;
            padding: 0;
            justify-content: space-between;
        }
        h2 img {
            height: 60%; /* Adjust image height to fit within header */
            margin-left: 20px; /* Add space between the text and image */
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="section1.php">Electronics</a>
        <a href="section2.php">Water Bottles</a>
        <a href="section3.php">Stationary</a>
        <a href="section4.php">Others</a>
        <a href="main.php">Back to Main Page</a>
        <a href="logout.php">Logout</a>
    </div>
    <h2>
        About
        <img src="img/inti_logo.png" alt="Inti Logo">
    </h2>
    <p>Everyday there's like a few items that scattered around inti waiting to be found by their owner. The problem is </p>
    <p>that they often don't know where to find them or where they lost it. So we're making a website for inti student </p>
    <p>to help student to find their lost item more easily. Not only that, this website also can track who claim the</p>
    <p>lost item as there will be a report in detail about the person. Moreover, if the owner not sure is it his or her</p>
    <p>own item, there will be a detail description of where it's found, when it's found and some detail on the item. </p>
    <a href="main.php">Back to Main Page</a> |
    <a href="logout.php">Logout</a>
</body>
</html>
