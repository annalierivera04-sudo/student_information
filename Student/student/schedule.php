<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */

requireLogin();

$id = (int)$_SESSION['user_id'];

$query = "SELECT schedules.*, courses.course_code, courses.course_name
          FROM schedules
          JOIN courses ON schedules.course_id = courses.id
          WHERE schedules.student_id = $id";
$schedules = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="profile.php" class="btn btn-secondary mb-3">← Back</a>

    <h2>Class Schedule</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <thead class="table-success">
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Subject</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($schedules)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['day']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= htmlspecialchars($row['room']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>