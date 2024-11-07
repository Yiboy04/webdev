<?php
require 'database_connection.php';

if (isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    
    $query = "SELECT * FROM report WHERE Item_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($report = $result->fetch_assoc()) {
            echo "<li><strong>Status:</strong> " . htmlspecialchars($report['Report_Status']) .
                 "<br><strong>Student Name:</strong> " . htmlspecialchars($report['Report_StudentName']) .
                 "<br><strong>Student ID:</strong> " . htmlspecialchars($report['Report_StudentID']) .
                 "<br><strong>Date:</strong> " . htmlspecialchars($report['Report_Date']) .
                 "</li><hr>";
        }
    } else {
        echo "<li>No reports available.</li>";
    }
}
?>
