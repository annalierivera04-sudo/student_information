<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */
requireAdmin();

// 1. DELETE COURSE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: courses.php");
    exit();
}

// 2. CREATE COURSE
if (isset($_POST['add_course'])) {
    $code = strtoupper(trim($_POST['course_code']));
    $name = trim($_POST['course_name']);
    $units = (int)$_POST['units'];

    $stmt = mysqli_prepare($conn, "INSERT INTO courses (course_code, course_name, units) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssi", $code, $name, $units);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: courses.php");
    exit();
}

// 3. UPDATE COURSE (NEW: Fulfills Full CRUD)
if (isset($_POST['update_course'])) {
    $id = (int)$_POST['course_id'];
    $code = strtoupper(trim($_POST['course_code']));
    $name = trim($_POST['course_name']);
    $units = (int)$_POST['units'];

    $stmt = mysqli_prepare($conn, "UPDATE courses SET course_code = ?, course_name = ?, units = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $code, $name, $units, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: courses.php");
    exit();
}

// 4. READ COURSES
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SIS Admin</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="students.php">Students</a>
            <a class="nav-link active" href="courses.php">Courses</a>
        </div>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Course Management</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourse">+ Add Course</button>
    </div>

    <!-- Live Search Filter -->
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="courseSearch" class="form-control" placeholder="Search course code or name...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered mb-0" id="coursesTable">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Units</th>
                        <th style="width: 170px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($courses)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['course_code']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= $row['units'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit-course"
                                    data-id="<?= $row['id'] ?>"
                                    data-code="<?= htmlspecialchars($row['course_code']) ?>"
                                    data-name="<?= htmlspecialchars($row['course_name']) ?>"
                                    data-units="<?= $row['units'] ?>">
                                Edit
                            </button>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this course?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 1. ADD COURSE MODAL -->
<div class="modal fade" id="addCourse">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add Course</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" class="form-control" placeholder="IT101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="course_name" class="form-control" placeholder="Web Development" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Units</label>
                        <input type="number" name="units" class="form-control" min="1" max="10" value="3" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_course" class="btn btn-success">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. EDIT COURSE MODAL -->
<div class="modal fade" id="editCourseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Edit Course</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="course_id" id="edit_course_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="edit_course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="course_name" id="edit_course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Units</label>
                        <input type="number" name="units" id="edit_course_units" class="form-control" min="1" max="10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_course" class="btn btn-warning">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JAVASCRIPT: Search & Modal Handling -->
<script>
    // Search courses
    document.getElementById('courseSearch').addEventListener('keyup', function () {
        const text = this.value.toLowerCase();
        const rows = document.querySelectorAll('#coursesTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(text) ? '' : 'none';
        });
    });

    // Populate edit course modal
    const editModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
    document.querySelectorAll('.btn-edit-course').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_course_id').value = this.dataset.id;
            document.getElementById('edit_course_code').value = this.dataset.code;
            document.getElementById('edit_course_name').value = this.dataset.name;
            document.getElementById('edit_course_units').value = this.dataset.units;
            editModal.show();
        });
    });
</script>
</body>
</html>