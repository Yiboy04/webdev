<?php
session_start();
require 'database_connection.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle deletion of an item
if (isset($_GET['delete'])) {
    $item_id_to_delete = (int)$_GET['delete'];

    // Delete item from the database
    $query = "DELETE FROM item WHERE Item_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id_to_delete);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch items for Electronics category (assume Category_ID = 1 for Electronics)
$category_id = 1;
$query = "SELECT * FROM item WHERE Category_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$items = $stmt->get_result();

// Handle form submission for adding, editing, and reporting
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

        // Insert or update item in the database
        if (isset($_POST['add_item'])) {
            // Add a new item
            $query = "INSERT INTO item (Item_Name, Item_Date, Item_Time, Item_Description, Item_Photo, Category_ID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $item_name, $item_date, $item_time, $item_description, $item_photo, $category_id);
            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Failed to add item: " . $stmt->error;
            }
        } elseif (isset($_POST['edit_item'])) {
            // Update an existing item
            $item_id = (int)$_POST['item_id'];
            if (empty($item_photo)) {
                $query = "UPDATE item SET Item_Name = ?, Item_Date = ?, Item_Time = ?, Item_Description = ? WHERE Item_ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssi", $item_name, $item_date, $item_time, $item_description, $item_id);
            } else {
                $query = "UPDATE item SET Item_Name = ?, Item_Date = ?, Item_Time = ?, Item_Description = ?, Item_Photo = ? WHERE Item_ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssi", $item_name, $item_date, $item_time, $item_description, $item_photo, $item_id);
            }
            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Failed to update item: " . $stmt->error;
            }
        }
        
        
        
        

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['submit_report'])) {
        // Handle report submission
        $item_id = (int)$_POST['item_id'];
        $report_status = htmlspecialchars($_POST['report_status']);
        $student_name = htmlspecialchars($_POST['student_name']);
        $student_id = htmlspecialchars($_POST['student_id']);
        $report_date = htmlspecialchars($_POST['report_date']);

        $query = "INSERT INTO report (Report_Status, Report_StudentName, Report_StudentID, Report_Date, Item_ID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $report_status, $student_name, $student_id, $report_date, $item_id);
        $stmt->execute();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
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
        .item-detail { border: 1px solid #ddd; border-radius: 8px; margin: 20px 0; padding: 20px; background-color: #f9f9f9; display: flex; flex-wrap: wrap; }
        .item-header { width: 100%; margin-bottom: 15px; }
        .item-header h3 { font-size: 24px; color: #333; }
        .item-header .item-category { font-size: 14px; color: #666; }
        .item-content { display: flex; flex-wrap: wrap; gap: 20px; width: 100%; }
        .item-image img { width: 150px; height: 150px; border-radius: 8px; object-fit: cover; border: 1px solid #ccc; }
        .item-info { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .item-info p { margin: 0; font-size: 16px; color: #444; }
        .item-actions { margin-top: 15px; width: 100%; }
        .item-actions a { color: #007bff; text-decoration: none; margin-right: 15px; font-size: 16px; }
        .item-actions a:hover { text-decoration: underline; }

        /* Modal styling */
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
        .close:hover, .close:focus { color: black; cursor: pointer; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin.php">Back to Main Page</a>
    <a href="Asection2.php">Water Bottles</a>
    <a href="Asection3.php">Stationary</a>
    <a href="Asection4.php">Others</a>
    <a href="admin_logout.php">Logout</a>
    <a href="#" onclick="openAddModal()">Add New Item</a>
</div>

<h2>Electronics</h2>
<p>This is the electronics section of the website.</p>

<div class="item-list">
    <h3>Added Items</h3>
    <?php while ($item = $items->fetch_assoc()): ?>
        <div class="item-detail">
            <!-- Item Header -->
            <div class="item-header">
                <h3>Item Name: <span class="item-name"><?= htmlspecialchars($item['Item_Name']) ?></span></h3>
                <p class="item-category">Category: Electronics</p>
            </div>

            <!-- Item Content -->
            <div class="item-content">
                <div class="item-image">
                    <?php if (!empty($item['Item_Photo'])): ?>
                        <img src="<?= htmlspecialchars($item['Item_Photo']) ?>" alt="<?= htmlspecialchars($item['Item_Name']) ?>">
                    <?php else: ?>
                        <img src="default-item.png" alt="No Image Available">
                    <?php endif; ?>
                </div>
                <div class="item-info">
                    <p><strong>Date Added:</strong> <?= htmlspecialchars($item['Item_Date']) ?></p>
                    <p><strong>Time Added:</strong> <?= htmlspecialchars($item['Item_Time']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($item['Item_Description']) ?></p>
                </div>
            </div>

            <!-- Item Actions -->
            <div class="item-actions">
                <a class="edit-btn" href="#" onclick="openEditModal(<?= $item['Item_ID'] ?>)">[Edit]</a>
                <a class="delete-btn" href="?delete=<?= $item['Item_ID'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">[Delete]</a>
                <a class="report-btn" href="#" onclick="openReportModal(<?= $item['Item_ID'] ?>)">[Report]</a>
                <a class="view-report-btn" href="#" onclick="openViewReportModal(<?= $item['Item_ID'] ?>)">[View Report]</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Modals for Add, Edit, Report, and View Reports -->
<div id="itemModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('itemModal')">&times;</span>
        <h2 id="modalTitle">Add New Item</h2>
        <form id="itemForm" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" id="item_id" name="item_id">
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

<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('reportModal')">&times;</span>
        <h2>Report Item</h2>
        <form method="post" action="">
            <input type="hidden" name="item_id" id="reportItemId">
            <label for="report_status">Report Status:</label>
            <select id="report_status" name="report_status" required>
                <option value="Available">Available</option>
                <option value="Taken">Taken</option>
            </select>
            <br><br>
            
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" required>
            <br><br>
            
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>
            <br><br>
            
            <label for="report_date">Date:</label>
            <input type="date" id="report_date" name="report_date" required>
            <br><br>
            
            <button type="submit" name="submit_report">Submit Report</button>
        </form>
    </div>
</div>

<div id="viewReportModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewReportModal')">&times;</span>
        <h2>View Reports</h2>
        <ul id="reportList"></ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openAddModal() {
    document.getElementById("itemModal").style.display = "block";
    document.getElementById("modalTitle").innerText = "Add New Item";
    document.getElementById("addItemBtn").style.display = "inline";
    document.getElementById("editItemBtn").style.display = "none";
    document.getElementById("itemForm").reset();
    document.getElementById("editIndex").value = ""; // Reset the hidden field for editing
 }


    function openEditModal(itemId) {
    // Reset the form
    document.getElementById("itemForm").reset();

    // Set modal title
    document.getElementById("modalTitle").innerText = "Edit Item";

    // Toggle buttons
    document.getElementById("addItemBtn").style.display = "none";
    document.getElementById("editItemBtn").style.display = "inline";

    // Fetch item details via AJAX
    $.ajax({
        url: "fetch_item.php", // A PHP script to fetch item details
        type: "POST",
        data: { item_id: itemId },
        dataType: "json",
        success: function(data) {
            if (data) {
                // Populate the form fields with the item data
                document.getElementById("item_id").value = data.Item_ID; // Hidden field
                document.getElementById("item_name").value = data.Item_Name;
                document.getElementById("item_date").value = data.Item_Date;
                document.getElementById("item_time").value = data.Item_Time;
                document.getElementById("item_description").value = data.Item_Description;
            }
        },
        error: function() {
            alert("Failed to load item details.");
        }
    });

    // Show the modal
    document.getElementById("itemModal").style.display = "block";
}


    function openReportModal(itemId) {
        document.getElementById("reportModal").style.display = "block";
        document.getElementById("reportItemId").value = itemId;
    }

    function openViewReportModal(itemId) {
        document.getElementById("viewReportModal").style.display = "block";
        let reportList = document.getElementById("reportList");
        reportList.innerHTML = "";

        // Fetch reports via AJAX
        $.ajax({
            url: "fetch_reports.php",
            type: "POST",
            data: { item_id: itemId },
            success: function(data) {
                if (data) {
                    reportList.innerHTML = data;
                } else {
                    reportList.innerHTML = "<li>No reports available.</li>";
                }
            }
        });
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }
</script>

</body>
</html>
