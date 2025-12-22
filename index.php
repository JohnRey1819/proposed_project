<?php
require_once 'db_connect.php';

$is_admin = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;

$today = date("Y-m-d");
$date = date("d-m-y");

$sql_total = "SELECT COUNT(student_id) AS total FROM students";
$result_total = mysqli_query($conn, $sql_total);
$total_students = mysqli_fetch_assoc($result_total)['total'];

$sql_present = "SELECT COUNT(student_id) AS present FROM attendance WHERE attendance_date = '$today'";
$result_present = mysqli_query($conn, $sql_present);
$present_students = mysqli_fetch_assoc($result_present)['present'];

$absent_students = $total_students - $present_students;
$attendance_status = $present_students > 0 ? 'bg-success' : 'bg-secondary';

$sql_students = "SELECT student_id, student_name FROM students ORDER BY student_name ASC";
$result_students = mysqli_query($conn, $sql_students);

$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'insert_success') {
        $message = '<div class="alert alert-success">✅ Student registered successfully!</div>';
    } elseif ($_GET['status'] == 'update_success') {
        $message = '<div class="alert alert-info">📝 Student updated successfully!</div>';
    } elseif ($_GET['status'] == 'delete_success') {
        $message = '<div class="alert alert-warning">🗑️ Student deleted successfully.</div>';
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
    <style>
        .icon-lg { font-size: 2rem; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-end mb-4">
        <?php if ($is_admin): ?>
            <a href="auth.php?action=logout" class="btn btn-danger">🔒 Admin Logout (<?= htmlspecialchars($_SESSION["username"]) ?>)</a>
        <?php else: ?>
            <a href="auth.php" class="btn btn-primary">🔑 Admin Login</a>
        <?php endif; ?>
        </div>
        
        <h1 class="text-center mb-5 text-primary">🏫 Attendance System Dashboard</h1>
        
        <?= $message ?>
        
        <h2 class="mb-4 text-secondary">Attendance Tracking (Today: <?= $date ?>)</h2>
        <div class="row g-4 mb-5">
            
            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-3 bg-white border-0">
                    <div class="d-flex align-items-center">
                        <span class="icon-lg text-primary me-3">👥</span>
                        <div>
                            <p class="text-muted mb-0">Total Students</p>
                            <h3 class="mb-0"><?= $total_students ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <a href="view_attendance.php?view=present" class="text-decoration-none text-dark">
                    <div class="card stat-card shadow-sm p-3 border-start border-5 border-success bg-white">
                        <div class="d-flex align-items-center">
                            <span class="icon-lg text-success me-3">✅</span>
                            <div>
                                <p class="text-muted mb-0">Present Today</p>
                                <h3 class="mb-0"><?= $present_students ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="view_attendance.php?view=absent" class="text-decoration-none text-dark">
                    <div class="card stat-card shadow-sm p-3 border-start border-5 border-danger bg-white">
                        <div class="d-flex align-items-center">
                            <span class="icon-lg text-danger me-3">❌</span>
                            <div>
                                <p class="text-muted mb-0">Absent Today</p>
                                <h3 class="mb-0"><?= $absent_students ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <?php if ($is_admin): ?>
        <div class="d-flex justify-content-between mb-4">
            <a href="take_attendance.php" class="btn btn-success btn-lg shadow">📅 Take Today's Attendance</a>
            <a href="attendance_action.php?action=clear" class="btn btn-warning shadow" onclick="return confirm('Are you sure you want to clear ALL recorded attendance for TODAY?');">🧹 Clear Today's Attendance</a>
        </div>
        
        <hr class="my-5">

        <h2 class="mb-4 text-secondary">Student Records Management</h2>
        
        <a href="insert.php" class="btn btn-primary mb-3 shadow-sm">➕ Register New Student</a>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">All Student Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <?php if ($is_admin): ?>
                                <th style="width: 150px;">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (mysqli_num_rows($result_students) > 0) {
                            while($row = mysqli_fetch_assoc($result_students)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                                if ($is_admin) {
                                    echo '<td>';
                                    echo '<a href="update.php?id=' . urlencode($row['student_id']) . '" class="btn btn-sm btn-info me-2 text-white" title="Edit Record">✏️</a>';
                                    echo '<form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this student?\');">';
                                    echo '<input type="hidden" name="student_id" value="' . htmlspecialchars($row['student_id']) . '">';
                                    echo '<button type="submit" class="btn btn-sm btn-danger" title="Delete Record">🗑️</button>';
                                    echo '</form>';
                                    echo '</td>';
                                }
                                echo "</tr>";
                            }
                        } else {
                            $colspan = $is_admin ? 3 : 2;
                            echo "<tr><td colspan='{$colspan}' class='text-center'>No students found in the database.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
    <title>Dashboard | Student Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .icon-lg { font-size: 2rem; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5 text-primary">🏫 Attendance System Dashboard</h1>
        
        <?= $message ?>
        
        <h2 class="mb-4 text-secondary">Attendance Tracking (Today: <?= $date ?>)</h2>
        <div class="row g-4 mb-5">
            
            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-3 bg-white border-0">
                    <div class="d-flex align-items-center">
                        <span class="icon-lg text-primary me-3">👥</span>
                        <div>
                            <p class="text-muted mb-0">Total Students</p>
                            <h3 class="mb-0"><?= $total_students ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <a href="view_attendance.php?view=present" class="text-decoration-none text-dark">
                    <div class="card stat-card shadow-sm p-3 border-start border-5 border-success bg-white">
                        <div class="d-flex align-items-center">
                            <span class="icon-lg text-success me-3">✅</span>
                            <div>
                                <p class="text-muted mb-0">Present Today</p>
                                <h3 class="mb-0"><?= $present_students ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="view_attendance.php?view=absent" class="text-decoration-none text-dark">
                    <div class="card stat-card shadow-sm p-3 border-start border-5 border-danger bg-white">
                        <div class="d-flex align-items-center">
                            <span class="icon-lg text-danger me-3">❌</span>
                            <div>
                                <p class="text-muted mb-0">Absent Today</p>
                                <h3 class="mb-0"><?= $absent_students ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="take_attendance.php" class="btn btn-success btn-lg shadow">📅 Take Today's Attendance</a>
            <a href="attendance_action.php?action=clear" class="btn btn-warning shadow" onclick="return confirm('Are you sure you want to clear ALL recorded attendance for TODAY?');">🧹 Clear Today's Attendance</a>
        </div>
        
        <hr class="my-5">

        <h2 class="mb-4 text-secondary">Student Records Management</h2>
        
        <a href="insert.php" class="btn btn-primary mb-3 shadow-sm">➕ Register New Student</a>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">All Student Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (mysqli_num_rows($result_students) > 0) {
                            while($row = mysqli_fetch_assoc($result_students)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                                echo '<td>';
                                echo '<a href="update.php?id=' . urlencode($row['student_id']) . '" class="btn btn-sm btn-info me-2 text-white" title="Edit Record">✏️</a>';
                                echo '<form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this student?\');">';
                                echo '<input type="hidden" name="student_id" value="' . htmlspecialchars($row['student_id']) . '">';
                                echo '<button type="submit" class="btn btn-sm btn-danger" title="Delete Record">🗑️</button>';
                                echo '</form>';
                                echo '</td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No students found in the database.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
