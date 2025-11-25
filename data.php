<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$action = $data['action'] ?? '';

if ($action === 'clear_attendance') {
    $date = date('Y-m-d');
    $stmt = $conn->prepare("DELETE FROM attendance WHERE attendance_date = ?");
    $stmt->bind_param("s", $date);

    if ($stmt->execute()) {
        $count = $stmt->affected_rows;
        echo json_encode(['success' => true, 'message' => "Successfully cleared attendance for $count student(s) for today ({$date})."]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error clearing attendance: " . $stmt->error]);
    }
    $stmt->close();
    close_db_connection($conn);
    exit();
}

if ($action === 'delete_student') {
    $id = $data['data']['id'] ?? '';
    $name = $data['data']['name'] ?? 'Student';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID is required for deletion.']);
        close_db_connection($conn);
        exit();
    }

    $conn->begin_transaction();

    try {
        $stmt_att = $conn->prepare("DELETE FROM attendance WHERE student_id = ?");
        $stmt_att->bind_param("s", $id);
        $stmt_att->execute();
        $stmt_att->close();

        $stmt_stud = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt_stud->bind_param("s", $id);
        $stmt_stud->execute();
        
        if ($stmt_stud->affected_rows === 0) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => "Could not find student '{$id}' to delete."]);
        } else {
            $conn->commit();
            echo json_encode(['success' => true, 'message' => "Student '{$name}' successfully deleted along with attendance records."]);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => "Transaction failed: " . $e->getMessage()]);
    } finally {
        if (isset($stmt_stud)) $stmt_stud->close();
        close_db_connection($conn);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid delete action.']);
close_db_connection($conn);
?>