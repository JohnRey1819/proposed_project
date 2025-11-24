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
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-success text-white rounded-top-4 border-0">
                <h5 class="modal-title">Confirm Attendance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <svg class="mx-auto" style="height: 48px; width: 48px; color: #198754;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="fs-5 mt-3 mb-1 text-secondary">Is this correct?</p>
                <h3 id="confirm-name" class="fs-2 fw-bolder text-dark"></h3>
                <p id="confirm-id" class="text-muted mb-4"></p>
                <div class="d-flex gap-3">
                    <button id="confirm-present-btn" onclick="markStudentPresentFromModal()" class="btn btn-success btn-lg flex-fill rounded-3">Confirm Present</button>
                    <button type="button" class="btn btn-outline-secondary btn-lg flex-fill rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4 border-0">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modal-msg-box" class="alert d-none text-center rounded-3" role="alert"></div>
                <input type="hidden" id="edit-original-id">
                <div class="mb-3">
                    <label for="edit-id" class="form-label fw-bold">Student ID</label>
                    <input type="text" class="form-control rounded-3" placeholder="e.g., 21-1-2414" id="edit-id" required>
                </div>
                <div class="mb-4">
                    <label for="edit-name" class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control rounded-3" id="edit-name" required>
                </div>
                <div class="d-grid gap-2">
                    <button onclick="saveEditedStudent()" class="btn btn-primary btn-lg rounded-3">Save Changes</button>
                    <button type="button" class="btn btn-outline-secondary btn-lg rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="clear-delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark rounded-top-4 border-0">
                <h5 class="modal-title" id="cd-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-4 text-secondary" id="cd-message">Are you sure?</p>
                <div class="d-flex justify-content-center gap-3">
                    <button id="cd-action-btn" class="btn btn-danger btn-lg rounded-3"></button>
                    <button type="button" class="btn btn-outline-secondary btn-lg rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
    
    
</body>
</html>  
