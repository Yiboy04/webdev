<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Define file paths for each section
$section_files = [
    "Electronics" => "electronics_items.txt",
    "Water Bottles" => "water_bottles_items.txt",
    "Stationary" => "stationary_items.txt",
    "Others" => "others_items.txt",
];

// Function to read items from a file
function readItemsFromFile($filename) {
    if (file_exists($filename)) {
        return file($filename, FILE_IGNORE_NEW_LINES);
    }
    return [];
}

// Handle form submission for adding a new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    // Get and validate section selection
    $section = isset($_POST['section']) ? trim($_POST['section']) : '';
    
    // Debugging: Print the section value to see if it's passed correctly
    // echo "Selected section: " . htmlspecialchars($section); exit;

    // Check if the section is valid
    if (!array_key_exists($section, $section_files)) {
        echo "Invalid section selected. Please try again.";
        exit();
    }

    // Retrieve and sanitize other form inputs
    $item_name = htmlspecialchars($_POST['item_name']);
    $item_date = htmlspecialchars($_POST['item_date']);
    $item_time = htmlspecialchars($_POST['item_time']);
    $item_description = htmlspecialchars($_POST['item_description']);
    $item_photo = '';

    // Handle file upload for photo
    if (isset($_FILES['item_photo']) && $_FILES['item_photo']['error'] === UPLOAD_ERR_OK) {
        $photo_filename = basename($_FILES['item_photo']['name']);
        $photo_path = 'uploads/' . $photo_filename;
        if (move_uploaded_file($_FILES['item_photo']['tmp_name'], $photo_path)) {
            $item_photo = $photo_path;
        }
    }

    // Save the item to the appropriate file
    $filename = $section_files[$section];
    $item_data = "Item: $item_name - Date: $item_date - Time: $item_time - Description: $item_description - Photo: $item_photo\n";
    file_put_contents($filename, $item_data, FILE_APPEND);

    // Refresh the page after adding the item
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Read items for the Electronics section
$items = readItemsFromFile($section_files['Electronics']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electronics Section</title>
    <style>
        /* Page styling */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .navbar { overflow: hidden; background-color: #333; }
        .navbar a { float: left; display: block; color: #f2f2f2; text-align: center; padding: 14px 16px; text-decoration: none; font-size: 25px; }
        .navbar a:hover { background-color: #ddd; color: black; }
        h2 { background-color: red; color: black; font-size: 50px; padding: 10px; margin: 0; }
        .item-list { margin: 20px; font-size: 18px; }
        .item { margin-bottom: 15px; }
        .item img { width: 100px; height: 100px; display: block; margin-top: 10px; }

        /* Modal styling */
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
        .close:hover, .close:focus { color: black; cursor: pointer; }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="main.php">Back to Main Page</a>
    <a href="section2.php">Water Bottles</a>
    <a href="section3.php">Stationary</a>
    <a href="section4.php">Others</a>
    <a href="logout.php">Logout</a>
    <a href="#" onclick="openModal()">Add New Item</a>
</div>

<!-- Section Header -->
<h2>Electronics</h2>
<p>This is the electronics section of the website.</p>

<!-- Display the added items -->
<div class="item-list">
    <h3>Added Items</h3>
    <ul>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): ?>
                <li class="item">
                    <?php
                    // Extract item details
                    preg_match("/Item: (.*?) - Date: (.*?) - Time: (.*?) - Description: (.*?) - Photo: (.*)/", $item, $matches);

                    // Display item details
                    if (isset($matches[1])) {
                        echo "<strong>Item:</strong> " . htmlspecialchars($matches[1]) . "<br>";
                        echo "<strong>Date:</strong> " . htmlspecialchars($matches[2]) . "<br>";
                        echo "<strong>Time:</strong> " . htmlspecialchars($matches[3]) . "<br>";
                        echo "<strong>Description:</strong> " . htmlspecialchars($matches[4]) . "<br>";

                        // Display photo if available
                        if (!empty($matches[5])) {
                            echo '<img src="' . htmlspecialchars($matches[5]) . '" alt="Item Photo">';
                        }
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No items added yet.</li>
        <?php endif; ?>
    </ul>
</div>

<!-- Modal for Adding Item -->
<div id="addItemModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add New Item</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="section">Section:</label>
            <select id="section" name="section" required>
                <option value="Electronics">Electronics</option>
                <option value="Water Bottles">Water Bottles</option>
                <option value="Stationary">Stationary</option>
                <option value="Others">Others</option>
            </select>
            <br><br>
            
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <br><br>
            
            <label for="item_date">Date:</label>
            <input type="date" id="item_date" name="item_date" required>
            <br><br>
            
            <label for="item_time">Time:</label>
            <input type="time" id="item_time" name="item_time" required>
            <br><br>
            
            <label for="item_description">Description:</label>
            <textarea id="item_description" name="item_description" required></textarea>
            <br><br>
            
            <label for="item_photo">Photo:</label>
            <input type="file" id="item_photo" name="item_photo" accept="image/*">
            <br><br>
            
            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>
</div>

<script>
    // JavaScript to open and close the modal
    function openModal() {
        document.getElementById("addItemModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("addItemModal").style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById("addItemModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
