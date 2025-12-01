<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $today = date("Y-m-d");
    
    if ($action == 'mark_present' && isset($_POST['student_id'])) {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);

        $sql_check = "SELECT id FROM attendance WHERE student_id = '$student_id' AND attendance_date = '$today'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            header("location: take_attendance.php?status=already_present");
            exit();
        }

        $sql_insert = "INSERT INTO attendance (student_id, attendance_date) VALUES ('$student_id', '$today')";
        
        if (mysqli_query($conn, $sql_insert)) {
            header("location: take_attendance.php?status=marked_present");
            exit();
        } else {
            if (mysqli_errno($conn) == 1062) {
                 header("location: take_attendance.php?status=already_present");
                 exit();
            }
            die("Error: " . mysqli_error($conn));
        }
    } else {
        header("location: take_attendance.php");
        exit();
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'clear') {
    $today = date("Y-m-d");
    $sql = "DELETE FROM attendance WHERE attendance_date = '$today'";
    
    if (mysqli_query($conn, $sql)) {
        header("location: take_attendance.php?status=clear_success");
        exit();
    } else {
        die("Error clearing attendance: " . mysqli_error($conn));
    }

} else {
    header("location: index.php");
    exit();
}

mysqli_close($conn);
?>
