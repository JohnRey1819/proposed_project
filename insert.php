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
?>
