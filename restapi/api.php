<?php
header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo) {
    $sql = "SELECT * FROM student";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

function handlePost($pdo, $input) {
    $sql = "INSERT INTO student (student_Name, student_Password) VALUES (:student_Name, :student_Password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_Name' => $input['student_Name'], 'student_Password' => $input['student_Password']]);
    echo json_encode(['message' => 'User created successfully']);
}

function handlePut($pdo, $input) {
    $sql = "UPDATE student SET student_Name = :student_Name, student_Password = :student_Password WHERE student_ID = :student_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_Name' => $input['student_Name'], 'student_Password' => $input['student_Password'], 'student_ID' => $input['student_ID']]);
    echo json_encode(['message' => 'User updated successfully']);
}

function handleDelete($pdo, $input) {
    $sql = "DELETE FROM student WHERE student_ID = :student_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_ID' => $input['student_ID']]);
    echo json_encode(['message' => 'User deleted successfully']);
}
?>