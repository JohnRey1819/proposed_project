<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'] ?? '';
$name = $data['name'] ?? '';

if (empty($id) || empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Student ID and Name are required.']);
    close_db_connection($conn);
    exit();
}

$check_stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
$check_stmt->bind_param("s", $id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $check_stmt->close();
    echo json_encode(['success' => false, 'message' => "Student ID '{$id}' is already registered."]);
    close_db_connection($conn);
    exit();
}
$check_stmt->close();

$stmt = $conn->prepare("INSERT INTO students (student_id, name) VALUES (?, ?)");
$stmt->bind_param("ss", $id, $name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => "Student '{$name}' registered successfully."]);
} else {
    echo json_encode(['success' => false, 'message' => "Error registering student: " . $stmt->error]);
}

$stmt->close();
close_db_connection($conn);
?>
