<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// Define the file where electronics items are stored
$filename = 'stationary_items.txt';

// Function to read the items from the file
function readItemsFromFile($filename) {
    if (file_exists($filename)) {
        return file($filename, FILE_IGNORE_NEW_LINES);
    }
    return [];
}

// Function to save the items to the file
function saveItemsToFile($filename, $items) {
    file_put_contents($filename, implode("\n", $items) . "\n");
}

// Initialize variables for editing
$edit_mode = true;
$edit_index = null;
$edit_name = '';
$edit_date = '';

// Read the items from the file
$items = readItemsFromFile($filename);

// If there are items, set the first item as the default value for the edit form
if (!empty($items)) {
    $edit_index = 0;
    preg_match("/Item: (.*?) - Date: (.*)/", $items[$edit_index], $matches);
    $edit_name = $matches[1];
    $edit_date = $matches[2];
}

// Check if an item is being deleted
if (isset($_GET['delete'])) {
    $indexToDelete = (int)$_GET['delete'];
    if (isset($items[$indexToDelete])) {
        unset($items[$indexToDelete]);
        $items = array_values($items); // Re-index array after deletion
        saveItemsToFile($filename, $items);
    }

    // Refresh the page after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Check if an item is being edited via URL query
if (isset($_GET['edit'])) {
    $edit_index = (int)$_GET['edit'];
    if (isset($items[$edit_index])) {
        preg_match("/Item: (.*?) - Date: (.*)/", $items[$edit_index], $matches);
        $edit_name = $matches[1];
        $edit_date = $matches[2];
    }
}

// Handle form submission for adding a new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $item_name = htmlspecialchars($_POST['item_name']);
    $item_date = htmlspecialchars($_POST['item_date']);
    if ($item_name && $item_date) {
        $items[] = "Item: $item_name - Date: $item_date";
        saveItemsToFile($filename, $items);
    }
}

// Handle form submission for editing an item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_item'])) {
    $indexToEdit = (int)$_POST['index'];
    $item_name = htmlspecialchars($_POST['item_name']);
    $item_date = htmlspecialchars($_POST['item_date']);
    if ($item_name && $item_date && isset($items[$indexToEdit])) {
        $items[$indexToEdit] = "Item: $item_name - Date: $item_date";
        saveItemsToFile($filename, $items);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Section 3</title>
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
        .item-list {
            margin: 20px;
            font-size: 18px;
        }
        .item-form {
            display: flex;
            justify-content: flex-start;
            margin: 20px;
            gap: 10px; /* Reduce the gap to make them as close as possible */
        }
        .form-container {
            padding: 10px;
            border: 1px solid #ccc;
            width: 250px; /* Make the forms smaller to fit better side by side */
        }
        .delete-btn {
            color: red;
            text-decoration: none;
        }
        .edit-btn {
            color: blue;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="Asection1.php">Electronics</a>
        <a href="Asection2.php">Water Bottles</a>
        <a href="admin.php">Back to Main Page</a>
        <a href="Asection4.php">Others</a>
        <a href="admin_logout.php">Logout</a>
    </div>
    <h2>Stationary</h2>
    <p>This is the stationary section of the website.</p>

    <!-- Form container for both Add and Edit forms -->
<div class="item-form">
    <!-- Add Form -->
    <div class="form-container">
        <h3>Add New Item</h3>
        <form method="post" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <br>
            <label for="item_date">Date:</label>
            <input type="date" id="item_date" name="item_date" required>
            <br><br>
            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>

    <!-- Edit Form (always displays first item or the selected item) -->
    <div class="form-container">
        <h3>Edit Item</h3>
        <form method="post" action="">
            <input type="hidden" name="index" value="<?php echo $edit_index !== null ? $edit_index : ''; ?>">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" value="<?php echo $edit_name; ?>" required>
            <br>
            <label for="item_date">Date:</label>
            <input type="date" id="item_date" name="item_date" value="<?php echo $edit_date; ?>" required>
            <br><br>
            <button type="submit" name="edit_item">Save Changes</button>
        </form>
    </div>
</div>

<!-- Display the added items with edit and delete buttons -->
<div class="item-list">
    <h3>Added Items</h3>
    <ul>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): ?>
                <li>
                    <?php echo htmlspecialchars($item); ?>
                    <a class="edit-btn" href="?edit=<?php echo $index; ?>">[Edit]</a>
                    <a class="delete-btn" href="?delete=<?php echo $index; ?>" onclick="return confirm('Are you sure you want to delete this item?');">[Delete]</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No items added yet.</li>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>
