<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$originalId = $data['originalId'] ?? '';
$newId = $data['newId'] ?? '';
$newName = $data['newName'] ?? '';

if (empty($originalId) || empty($newId) || empty($newName)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required for update.']);
    close_db_connection($conn);
    exit();
}

if ($originalId !== $newId) {
    $check_stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ? AND student_id != ?");
    $check_stmt->bind_param("ss", $newId, $originalId);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $check_stmt->close();
        echo json_encode(['success' => false, 'message' => "New Student ID '{$newId}' is already in use by another student."]);
        close_db_connection($conn);
        exit();
    }
    $check_stmt->close();
}

$stmt = $conn->prepare("UPDATE students SET student_id = ?, name = ? WHERE student_id = ?");
$stmt->bind_param("sss", $newId, $newName, $originalId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => "Student '{$newName}' ({$newId}) updated successfully."]);
} else {
    echo json_encode(['success' => false, 'message' => "Error updating student: " . $stmt->error]);
}

$stmt->close();
close_db_connection($conn);
?>