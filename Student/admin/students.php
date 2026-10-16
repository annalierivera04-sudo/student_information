<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */
requireAdmin();

$message = "";

// 1. DELETE STUDENT
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'student'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: students.php");
    exit();
}

// 2. CREATE STUDENT
if (isset($_POST['add_student'])) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Username already exists.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, username, password, role, course, year_level) VALUES (?, ?, ?, 'student', ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $username, $password, $course, $year_level);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: students.php");
        exit();
    }
}

// 3. UPDATE STUDENT
if (isset($_POST['update_student'])) {
    $id = (int)$_POST['student_id'];
    $full_name = trim($_POST['full_name']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);

    $stmt = mysqli_prepare($conn, "UPDATE users SET full_name = ?, course = ?, year_level = ? WHERE id = ? AND role = 'student'");
    mysqli_stmt_bind_param($stmt, "sssi", $full_name, $course, $year_level, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: students.php");
    exit();
}

// 4. READ STUDENTS
$students = mysqli_query($conn, "SELECT * FROM users WHERE role = 'student' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SIS Admin</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link active" href="students.php">Students</a>
            <a class="nav-link" href="courses.php">Courses</a>
        </div>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudent">+ Add Student</button>
    </div>

    <!-- Live Search Filter -->
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="searchInput" class="form-control" placeholder="Search student by name, username, course...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-0" id="studentTable">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th style="width: 170px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['year_level']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit"
                                    data-id="<?= $row['id'] ?>"
                                    data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                    data-course="<?= htmlspecialchars($row['course']) ?>"
                                    data-year="<?= htmlspecialchars($row['year_level']) ?>">
                                Edit
                            </button>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Student -->
<div class="modal fade" id="addStudent">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add Student</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" name="course" class="form-control" placeholder="BSIT" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year Level</label>
                        <select name="year_level" class="form-select" required>
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_student" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Student -->
<div class="modal fade" id="editStudentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Edit Student</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="student_id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" name="course" id="edit_course" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year Level</label>
                        <select name="year_level" id="edit_year" class="form-select" required>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_student" class="btn btn-warning">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Live Search Filter (JavaScript DOM manipulation)
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentTable tbody tr');

        rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(searchText) ? '' : 'none';
        });
    });

    // Populate Edit Modal
    const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            document.getElementById('edit_name').value = this.getAttribute('data-name');
            document.getElementById('edit_course').value = this.getAttribute('data-course');
            document.getElementById('edit_year').value = this.getAttribute('data-year');
            editModal.show();
        });
    });
</script>
</body>
</html>