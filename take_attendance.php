<?php
require_once 'db_connect.php';

$today = date("y-m-d");

$sql = "
    SELECT s.student_id, s.student_name, 
           CASE WHEN a.attendance_date IS NOT NULL THEN 'Present' ELSE 'Absent' END as status
    FROM students s
    LEFT JOIN attendance a ON s.student_id = a.student_id AND a.attendance_date = '$today'
    ORDER BY s.student_name ASC
";
$result = mysqli_query($conn, $sql);

$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'marked_present') {
        $message = '<div class="alert alert-success">Student marked present successfully!</div>';
    } elseif ($_GET['status'] == 'already_present') {
        $message = '<div class="alert alert-info">Student was already marked present.</div>';
    } elseif ($_GET['status'] == 'clear_success') {
        $message = '<div class="alert alert-warning">Attendance successfully cleared for today.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Take Attendance for <?= $today ?></h2>
        <?= $message ?>
        
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <div class="card shadow-sm">
            <div class="card-header">
                All Students
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $is_present = ($row['status'] == 'Present');
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                            echo '<td><span class="badge ' . ($is_present ? 'bg-success' : 'bg-danger') . '">' . $row['status'] . '</span></td>';
                            echo '<td>';
                            if (!$is_present) {
                                echo '<form action="attendance_action.php" method="POST" class="d-inline">';
                                echo '<input type="hidden" name="action" value="mark_present">';
                                echo '<input type="hidden" name="student_id" value="' . htmlspecialchars($row['student_id']) . '">';
                                echo '<button type="submit" class="btn btn-sm btn-success">Mark Present</button>';
                                echo '</form>';
                            } else {
                                echo '<button class="btn btn-sm btn-outline-secondary" disabled>Present</button>';
                            }
                            echo '</td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No students found.</td></tr>";
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
