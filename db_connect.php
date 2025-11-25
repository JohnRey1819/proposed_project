<?php
$host = 'localhost';
$username = '';
$password = '';
$dbname = 'attendance_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Database Connection failed: " . $conn->connect_error]);
    exit();
}

$conn->set_charset("utf8");

function close_db_connection($conn) {
    $conn->close();
}

?>