<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <style>
        /* Page styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 20px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        h2 {
            font-family: 'Georgia', serif;
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
        
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-content form label {
            display: block;
            margin-top: 10px;
        }
        .modal-content form input,
        .modal-content form textarea,
        .modal-content form select,
        .modal-content form button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .modal-content form button {
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 15px;
        }
        .modal-content form button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="Asection1.php">Electronics</a>
        <a href="Asection2.php">Water Bottles</a>
        <a href="Asection3.php">Stationary</a>
        <a href="Asection4.php">Others</a>
        <a href="admin_logout.php">Logout</a>
        <a href="#" id="addItemButton" onclick="openModal()">+</a>
    </div>

    <h2>
        Admin Page
        <img src="img/inti_logo.png" alt="Inti Logo">
    </h2>

    <div class="center-text">
        <p>The website where you find your lost item in INTI Penang.</p>
        <img src="img/inti.jpg" alt="Inti">
    </div>

    <!-- Modal for Adding Item -->
    <div id="addItemModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add New Item</h2>
            <form action="add_item.php" method="post" enctype="multipart/form-data">
                <label for="itemSection">Section:</label>
                <select id="itemSection" name="item_section" required>
                    <option value="Electronics">Electronics</option>
                    <option value="Water Bottles">Water Bottles</option>
                    <option value="Stationary">Stationary</option>
                    <option value="Others">Others</option>
                </select>

                <label for="itemName">Item Name:</label>
                <input type="text" id="itemName" name="item_name" required>

                <label for="itemDate">Date:</label>
                <input type="date" id="itemDate" name="item_date" required>

                <label for="itemTime">Time:</label>
                <input type="time" id="itemTime" name="item_time" required>

                <label for="itemDescription">Description:</label>
                <textarea id="itemDescription" name="item_description" required></textarea>

                <label for="itemPhoto">Photo:</label>
                <input type="file" id="itemPhoto" name="item_photo" accept="image/*" required>

                <button type="submit">Add Item</button>
            </form>
        </div>
    </div>

    <script>
        // Open the modal
        function openModal() {
            document.getElementById("addItemModal").style.display = "block";
        }

        // Close the modal
        function closeModal() {
            document.getElementById("addItemModal").style.display = "none";
        }

        // Close the modal if user clicks outside the modal content
        window.onclick = function(event) {
            var modal = document.getElementById("addItemModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
