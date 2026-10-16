<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */

requireLogin();

$id = (int)$_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$student = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand fw-bold">Student Information System</span>
        <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h2>My Profile</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h4><?= htmlspecialchars($student['full_name']) ?></h4>
            <hr>
            <p><strong>Username:</strong> <?= htmlspecialchars($student['username']) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($student['course']) ?></p>
            <p><strong>Year Level:</strong> <?= htmlspecialchars($student['year_level']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($student['role']) ?></p>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="grades.php" class="btn btn-primary">My Grades</a>
        <a href="schedule.php" class="btn btn-success">Class Schedule</a>
        <a href="attendance.php" class="btn btn-warning">Attendance</a>
    </div>
</div>

</body>
</html>