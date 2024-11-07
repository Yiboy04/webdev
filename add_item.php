<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection settings
$servername = "localhost";
$db_username = "root"; // replace with your MySQL username
$db_password = ""; // replace with your MySQL password
$dbname = "lost_and_found";

// Create a connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['item_section'] ?? '';
    $item_name = htmlspecialchars($_POST['item_name']);
    $item_date = htmlspecialchars($_POST['item_date']);
    $item_time = htmlspecialchars($_POST['item_time']);
    $item_description = htmlspecialchars($_POST['item_description']);
    $item_photo = '';

    // Retrieve the Category_ID based on the selected section (e.g., "Electronics" -> Category_ID = 1)
    $stmt = $conn->prepare("SELECT Category_ID FROM Category WHERE Category_Name = ?");
    $stmt->bind_param("s", $section);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $category_row = $result->fetch_assoc();
        $category_id = $category_row['Category_ID'];
    } else {
        echo "Invalid section selected.";
        exit();
    }
    $stmt->close();

    // Handle file upload for photo
    if (isset($_FILES['item_photo']) && $_FILES['item_photo']['error'] === UPLOAD_ERR_OK) {
        $photo_filename = basename($_FILES['item_photo']['name']);
        $photo_path = 'uploads/' . $photo_filename;
        if (move_uploaded_file($_FILES['item_photo']['tmp_name'], $photo_path)) {
            $item_photo = $photo_path;
        }
    }

    // Insert item into the Item table
    $stmt = $conn->prepare("INSERT INTO Item (Item_Name, Item_Date, Item_Time, Item_Description, Item_Photo, Category_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $item_name, $item_date, $item_time, $item_description, $item_photo, $category_id);

    if ($stmt->execute()) {
        // Redirect back to the main admin page after adding the item
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
