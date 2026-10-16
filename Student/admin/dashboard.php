<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */

requireAdmin();

$students_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'");
$students_count = mysqli_fetch_assoc($students_query)['total'];

$courses_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM courses");
$courses_count = mysqli_fetch_assoc($courses_query)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SIS Admin</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link active" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="students.php">Students</a>
            <a class="nav-link" href="courses.php">Courses</a>
        </div>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card text-bg-primary shadow-sm p-3">
                <div class="card-body">
                    <h4>Total Students</h4>
                    <h1 class="display-4"><?= $students_count ?></h1>
                    <a href="students.php" class="btn btn-light btn-sm mt-2">Manage Students</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card text-bg-success shadow-sm p-3">
                <div class="card-body">
                    <h4>Total Courses</h4>
                    <h1 class="display-4"><?= $courses_count ?></h1>
                    <a href="courses.php" class="btn btn-light btn-sm mt-2">Manage Courses</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>