<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */

requireLogin();

$id = (int)$_SESSION['user_id'];

$query = "SELECT grades.grade, courses.course_code, courses.course_name, courses.units
          FROM grades
          JOIN courses ON grades.course_id = courses.id
          WHERE grades.student_id = $id";
$grades = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="profile.php" class="btn btn-secondary mb-3">← Back</a>

    <h2>My Grades</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Units</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($grades)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['course_code']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= $row['units'] ?></td>
                        <td><strong><?= htmlspecialchars($row['grade']) ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>