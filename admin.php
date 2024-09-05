<?php
// Sample credentials for admin
$adminUsername = "admin";
$adminPassword = "123";

// Fetch the submitted username and password from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username and password are correct
if ($username === $adminUsername && $password === $adminPassword) {
    // Successful login
    echo "<h1>Welcome, Admin!</h1>";
    echo "<p>You have successfully logged in.</p>";
} else {
    // Failed login
    echo "<h1>Login Failed</h1>";
    echo "<p>Incorrect username or password. Please try again.</p>";
    echo "<a href='login.php'>Back to Login</a>";
}
?>
