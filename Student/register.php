<?php
session_start();

require_once "config/database.php";

$message = "";
$success = "";

if (isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);

    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) > 0) {
        $message = "Username is already taken.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, username, password, role, course, year_level) VALUES (?, ?, ?, 'student', ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "sssss", $full_name, $username, $hashed_password, $course, $year_level);

        if (mysqli_stmt_execute($insert_stmt)) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Something went wrong. Please try again.";
        }
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm border rounded-3 p-4 bg-white">

                <h2 class="text-center text-primary fw-bold mb-4 h4">Student Registration</h2>

                <?php if ($message != ""): ?>
                    <div class="alert alert-danger text-center mb-3"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Course</label>
                        <input type="text" name="course" class="form-control" placeholder="BSIT" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Year Level</label>
                        <select name="year_level" class="form-select" required>
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100 fw-bold py-2">Register</button>
                </form>

                <div class="text-center mt-3 text-muted small">
                    Already have an account? <a href="login.php" class="text-decoration-none fw-bold">Login</a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>