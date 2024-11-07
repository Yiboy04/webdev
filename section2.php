<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Define the file where electronics items are stored
$filename = 'waterbottles_items.txt';

// Function to read the items from the file
function readItemsFromFile($filename) {
    if (file_exists($filename)) {
        return file($filename, FILE_IGNORE_NEW_LINES);
    }
    return [];
}

// Read the items to display them
$items = readItemsFromFile($filename);
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
            background-color: red;
            height: 10vh; 
            color: black;
            display: flex;
            font-size: 50px;
            text-align: left;
            margin: 0;
            padding: 0;
        }
        p {
            font-size: 50px;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="section1.php">Electronics</a>
        <a href="main.php">Back to Main Page</a>
        <a href="section3.php">Stationary</a>
        <a href="section4.php">Others</a>
        <a href="about.php">About</a>
        <a href="logout.php">Logout</a>
    </div>
    <h2>Water Bottles</h2>
    <p>This is the water bottles section of the website.</p>

    <!-- Display the added items -->
<div class="item-list">
    <h3>Available Water Bottles Items</h3>
    <ul>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No items available.</li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>
