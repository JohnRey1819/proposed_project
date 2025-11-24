<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'] ?? '';
$action = $data['action'] ?? '';
$date = date('Y-m-d');

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required.']);
    close_db_connection($conn);
    exit();
}

if ($action === 'lookup') {
    $stmt = $conn->prepare("SELECT student_id, name FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode(['success' => true, 'message' => 'Student found.', 'data' => ['id' => $student['student_id'], 'name' => $student['name']]]);
    } else {
        echo json_encode(['success' => false, 'message' => "Student ID '{$id}' not found. Please register first."]);
    }
    $stmt->close();
    close_db_connection($conn);
    exit();
}
