<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="card app-card mx-auto">
        <div class="card-body p-4 p-md-5">
            <h1 class="text-center text-dark mb-4 fs-3 fw-bold">Student Attendance Portal</h1>
            <div id="msg-box" class="alert d-none text-center rounded-3" role="alert"></div>

            <ul class="nav nav-pills justify-content-center gap-2 mb-4" id="pills-tab" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#present" type="button" onclick="loadData('present')">Mark Present</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#register" type="button">Register</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#view-present" type="button" onclick="loadData('get_present')">Present Today</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#view-absent" type="button" onclick="loadData('get_absent')">Absent Today</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#view-all" type="button" onclick="loadData('get_all_students')">All Students</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="present">
                    <div class="p-4 rounded-3 border border-primary-subtle bg-primary-subtle">
                        <form onsubmit="event.preventDefault(); submitPresent();">
                            <div class="mb-3">
                                <label for="signin-id" class="form-label fw-bold">Enter Student ID</label>
                                <input type="text" class="form-control form-control-lg rounded-3" id="signin-id" placeholder="e.g., 24-1-2414" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm">Mark as Present</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="register">
                    <div class="p-4 rounded-3 border bg-light">
                        <form onsubmit="event.preventDefault(); submitRegister();">
                            <div class="mb-3">
                                <label for="reg-id" class="form-label fw-bold">Student ID</label>
                                <input type="text" class="form-control rounded-3" id="reg-id" required>
                            </div>
                            <div class="mb-4">
                                <label for="reg-name" class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control rounded-3" id="reg-name" required>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100 rounded-3">Register Student</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="view-present">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-success fw-bold mb-0">Students Present Today</h4>
                        <button id="clear-btn" class="btn btn-sm btn-outline-danger rounded-pill d-none" onclick="openClearDeleteModal('clear_attendance', null)">Clear All</button>
                    </div>
                    <ul id="present-list" class="list-group"></ul>
                </div>
                <div class="tab-pane fade" id="view-absent">
                    <h4 class="text-danger fw-bold mb-3">Students Absent Today</h4>
                    <ul id="absent-list" class="list-group"></ul>
                </div>
                <div class="tab-pane fade" id="view-all">
                    <h4 class="text-primary fw-bold mb-3">All Registered Students</h4>
                    <ul id="all-list" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
    
</body>
</html>  
