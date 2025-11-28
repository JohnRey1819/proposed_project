
<?php
require_once 'db_connect.php';

$view = isset($_GET['view']) && in_array($_GET['view'], ['present', 'absent']) ? $_GET['view'] : 'present';
$today = date("y-m-d");

if ($view == 'present') {
    $sql = "
        SELECT s.student_id, s.student_name 
        FROM students s
        INNER JOIN attendance a ON s.student_id = a.student_id AND a.attendance_date = '$today'
        ORDER BY s.student_name ASC
    ";
    $title = "Present Students Today ({$today})";
    $alert_class = "alert-success";
} else { 
    $sql = "
        SELECT s.student_id, s.student_name
        FROM students s
        LEFT JOIN attendance a ON s.student_id = a.student_id AND a.attendance_date = '$today'
        WHERE a.student_id IS NULL
        ORDER BY s.student_name ASC
    ";
    $title = "Absent Students Today ({$today})";
    $alert_class = "alert-danger";
}

$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4"><?= $title ?></h2>
        
        <div class="alert <?= $alert_class ?>">
            Total Students <?= $view ?>: <?= $count ?>
        </div>
        
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="card shadow-sm">
            <div class="card-header">
                Student List
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($count > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>No students were found for this view.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
