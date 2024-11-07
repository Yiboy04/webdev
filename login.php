<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: main.php");
    exit();
}

// Database connection details
$servername = "localhost";
$db_username = "root"; // replace with your MySQL username
$db_password = ""; // replace with your MySQL password
$dbname = "lost_and_found";

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute a query to fetch the student data
    $sql = "SELECT * FROM Student WHERE Student_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the student exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password (if stored as plain text, use `$password == $row['Student_Password']`)
        if ($password == $row['Student_Password']) { // Replace with password_verify if password is hashed
            // Set session and redirect to main page
            $_SESSION['username'] = $username;
            header("Location: main.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }
    
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .image-container {
            flex: 1;
            text-align: center;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
        }

        .form-container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container input[type="text"], 
        .form-container input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-container input[type="submit"] {
            padding: 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .admin-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px; /* Spacing from the login button */
            background-color: #555; /* Darker background for admin button */
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }

        .admin-button:hover {
            background-color: #666; /* Slightly lighter on hover */
        }

        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="img/inti_logo.png" alt="Inti Logo">
        </div>
        <div class="form-container">
            <h2>Login</h2>
            <form method="post" action="login.php">
                Username: <input type="text" name="username" required><br><br>
                Password: <input type="password" name="password" required><br><br>
                <input type="submit" value="Login">
            </form>
            <a href="admin_login.php" class="admin-button">Admin Login</a>
            <?php
            if (!empty($login_error)) {
                echo "<p class='error-message'>$login_error</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
