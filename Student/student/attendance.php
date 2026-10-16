<?php
require_once "../config/database.php";
require_once "../includes/auth.php";

/** @var mysqli $conn */

requireLogin();

$id = (int)$_SESSION['user_id'];

$query = "SELECT * FROM attendance WHERE student_id = $id ORDER BY date DESC";
$attendance = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="profile.php" class="btn btn-secondary mb-3">← Back</a>

    <h2>Attendance</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <thead class="table-warning">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($attendance)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Present'): ?>
                                <span class="badge bg-success">Present</span>
                            <?php elseif ($row['status'] == 'Absent'): ?>
                                <span class="badge bg-danger">Absent</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Late</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['remarks']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>