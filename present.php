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
