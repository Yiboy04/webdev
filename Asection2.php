<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// Define file paths for each section
$section_files = [
    "Electronics" => "electronics_items.txt",
    "Water Bottles" => "water_bottles_items.txt",
    "Stationary" => "stationary_items.txt",
    "Others" => "others_items.txt",
];

$filename = $section_files['Water Bottles'];

// Function to read items from a file
function readItemsFromFile($filename) {
    if (file_exists($filename)) {
        return file($filename, FILE_IGNORE_NEW_LINES);
    }
    return [];
}

// Function to save items to a file
function saveItemsToFile($filename, $items) {
    file_put_contents($filename, implode("\n", $items) . "\n");
}

// Handle form submission for adding/editing/deleting items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_item']) || isset($_POST['edit_item'])) {
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

        // If editing, update the selected item
        if (isset($_POST['edit_item'])) {
            $indexToEdit = (int)$_POST['index'];
            $items = readItemsFromFile($filename);

            // Update the item details
            if (isset($items[$indexToEdit])) {
                $items[$indexToEdit] = "Item: $item_name - Date: $item_date - Time: $item_time - Description: $item_description - Photo: $item_photo";
                saveItemsToFile($filename, $items);
            }
        } else {
            // If adding, append the new item
            $item_data = "Item: $item_name - Date: $item_date - Time: $item_time - Description: $item_description - Photo: $item_photo";
            file_put_contents($filename, $item_data . "\n", FILE_APPEND);
        }

        // Refresh the page after adding or editing
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle deletion of an item
if (isset($_GET['delete'])) {
    $indexToDelete = (int)$_GET['delete'];
    $items = readItemsFromFile($filename);
    if (isset($items[$indexToDelete])) {
        unset($items[$indexToDelete]);
        $items = array_values($items); // Re-index array after deletion
        saveItemsToFile($filename, $items);
    }

    // Refresh the page after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Load items for display
$items = readItemsFromFile($filename);
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
        h2 { background-color: #ddd; color: black; font-size: 50px; padding: 10px; margin: 0; }
        .item-list { margin: 20px; font-size: 18px; }
        .item { margin-bottom: 15px; }
        .item img { width: 100px; height: 100px; display: block; margin-top: 10px; }
        .edit-btn, .delete-btn { color: blue; text-decoration: none; margin-right: 10px; cursor: pointer; }

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
    <a href="Asection1.php">Electronics</a>
    <a href="admin.php">Back to Main Page</a>
    <a href="Asection3.php">Stationary</a>
    <a href="Asection4.php">Others</a>
    <a href="admin_logout.php">Logout</a>
    <a href="#" onclick="openAddModal()">Add New Item</a>
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
                    <a class="edit-btn" href="#" onclick="openEditModal(<?php echo $index; ?>)">[Edit]</a>
                    <a class="delete-btn" href="?delete=<?php echo $index; ?>" onclick="return confirm('Are you sure you want to delete this item?');">[Delete]</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No items added yet.</li>
        <?php endif; ?>
    </ul>
</div>

<!-- Add/Edit Modal -->
<div id="itemModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Add New Item</h2>
        <form id="itemForm" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" id="editIndex" name="index">
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
            
            <button type="submit" name="add_item" id="addItemBtn">Add Item</button>
            <button type="submit" name="edit_item" id="editItemBtn" style="display:none;">Save Changes</button>
        </form>
    </div>
</div>

<script>
    // Open the Add Modal
    function openAddModal() {
        document.getElementById("itemModal").style.display = "block";
        document.getElementById("modalTitle").innerText = "Add New Item";
        document.getElementById("addItemBtn").style.display = "inline";
        document.getElementById("editItemBtn").style.display = "none";
        document.getElementById("itemForm").reset();
    }

    // Open the Edit Modal
    function openEditModal(index) {
        document.getElementById("itemModal").style.display = "block";
        document.getElementById("modalTitle").innerText = "Edit Item";
        document.getElementById("addItemBtn").style.display = "none";
        document.getElementById("editItemBtn").style.display = "inline";
        
        // Populate form with item details
        let items = <?php echo json_encode($items); ?>;
        let item = items[index];
        let matches = item.match(/Item: (.*?) - Date: (.*?) - Time: (.*?) - Description: (.*?) - Photo: (.*)/);
        
        document.getElementById("editIndex").value = index;
        document.getElementById("item_name").value = matches[1];
        document.getElementById("item_date").value = matches[2];
        document.getElementById("item_time").value = matches[3];
        document.getElementById("item_description").value = matches[4];
    }

    // Close the Modal
    function closeModal() {
        document.getElementById("itemModal").style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById("itemModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
