<?php
require_once 'db_connect.php';

$id = $name = $error = '';

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id_to_fetch = trim($_GET["id"]);
    
    $sql = "SELECT student_id, student_name FROM students WHERE student_id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        $param_id = $id_to_fetch;
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result);
                $id = $row["student_id"];
                $name = $row["student_name"];
            } else {
                header("location: index.php?tab=students");
                exit();
            }
        } else {
            $error = "Error fetching data.";
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original_id = trim($_POST["original_id"]);
    $new_id = trim($_POST["student_id"]);
    $new_name = trim($_POST["student_name"]);

    if (empty($new_id) || empty($new_name)) {
        $error = "Please fill out both Student ID and Name.";
    } else {
        $sql = "UPDATE students SET student_id = ?, student_name = ? WHERE student_id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_new_id, $param_new_name, $param_original_id);
            $param_new_id = $new_id;
            $param_new_name = $new_name;
            $param_original_id = $original_id;

            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php?status=update_success&tab=students");
                exit();
            } else {
                $error = "Error: Could not update student. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h2 class="mb-4">Update Student Details</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="original_id" value="<?= htmlspecialchars($id); ?>">
                
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($id); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" value="<?= htmlspecialchars($name); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Details</button>
                <a href="index.php?tab=students" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
