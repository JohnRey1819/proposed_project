<?php
require_once 'db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST["student_id"]);
    $name = trim($_POST["student_name"]);

    if (empty($id) || empty($name)) {
        $error = "Please fill out both Student ID and Name.";
    } else {
        $sql_check = "SELECT student_id FROM students WHERE student_id = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $param_id);
        $param_id = $id;

        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) == 1) {
            $error = "A student with this ID already exists.";
        } else {
            $sql_insert = "INSERT INTO students (student_id, student_name) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "ss", $param_id, $param_name);
            $param_id = $id;
            $param_name = $name;

            if (mysqli_stmt_execute($stmt_insert)) {
                header("location: index.php?status=insert_success&tab=students");
                exit();
            } else {
                $error = "Error: Could not register student. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h2 class="mb-4">Register New Student</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" required>
                </div>
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="index.php?tab=students" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
