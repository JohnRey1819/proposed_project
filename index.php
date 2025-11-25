<?php
require_once 'db_connect.php';

$sql = "SELECT student_id, student_name FROM students ORDER BY student_name ASC";
$result = mysqli_query($conn, $sql);

$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'insert_success') {
        $message = '<div class="alert alert-success">Student registered successfully!</div>';
    } elseif ($_GET['status'] == 'update_success') {
        $message = '<div class="alert alert-success">Student updated successfully!</div>';
    } elseif ($_GET['status'] == 'delete_success') {
        $message = '<div class="alert alert-warning">Student deleted successfully.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Attendance System Dashboard</h2>
        <?= $message ?>

        <div class="d-flex justify-content-between mb-4">
            <a href="take_attendance.php" class="btn btn-success btn-lg">Take Today's Attendance</a>
            <div>
                <a href="view_attendance.php?view=present" class="btn btn-info me-2">Present Students</a>
                <a href="view_attendance.php?view=absent" class="btn btn-danger me-2">Absent Students</a>
                <a href="attendance_action.php?action=clear" class="btn btn-warning" onclick="return confirm('Are you sure you want to clear TODAY\'s attendance?');">Clear Today's Attendance</a>
            </div>
        </div>

        <hr>

        <h3 class="mb-3">Student Management (CRUD)</h3>
        <a href="insert.php" class="btn btn-primary mb-3">Add New Student</a>

        <div class="card shadow-sm">
            <div class="card-header">
                All Students List
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                            echo '<td>';
                            echo '<a href="update.php?id=' . urlencode($row['student_id']) . '" class="btn btn-sm btn-info me-2">Edit</a>';
                            echo '<form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this student?\');">';
                            echo '<input type="hidden" name="student_id" value="' . htmlspecialchars($row['student_id']) . '">';
                            echo '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                            echo '</form>';
                            echo '</td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No students found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
