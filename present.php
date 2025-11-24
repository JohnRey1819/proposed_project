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

if ($action === 'check_attendance') {
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? AND attendance_date = ?");
    $stmt->bind_param("ss", $id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => true, 'message' => "Student '{$id}' is ALREADY PRESENT today.", 'data' => true]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Student is not yet present today.', 'data' => false]);
    }
    $stmt->close();
    close_db_connection($conn);
    exit();
}

if ($action === 'mark_present') {
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, attendance_date) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Student '{$id}' marked as PRESENT for today, {$date}.", 'data' => ['id' => $id, 'date' => $date]]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error marking attendance: " . $stmt->error]);
    }
    $stmt->close();
    close_db_connection($conn);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid attendance action.']);
close_db_connection($conn);
?>
