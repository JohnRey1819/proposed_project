<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["student_id"]) && !empty(trim($_POST["student_id"]))) {
        require_once 'db_connect.php';

        $id_to_delete = trim($_POST["student_id"]);

        $sql = "DELETE FROM students WHERE student_id = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            
            $param_id = $id_to_delete;
            
            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php?status=delete_success&tab=students");
                exit();
            } else {
                echo "Error: Could not delete record. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    } else {
        header("location: index.php?tab=students");
        exit();
    }
} else {
    header("location: index.php?tab=students");
    exit();
}
?>
