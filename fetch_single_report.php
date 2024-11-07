<?php
require 'database_connection.php';

if (!isset($_POST['report_id'])) {
    exit("No report ID provided.");
}

$report_id = (int)$_POST['report_id'];
$query = "SELECT * FROM report WHERE Report_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result);
?>
