<?php
require_once "config/database.php";

$admin_hash = password_hash("admin123", PASSWORD_DEFAULT);
$student_hash = password_hash("student123", PASSWORD_DEFAULT);

mysqli_query($conn, "UPDATE users SET password = '$admin_hash' WHERE username = 'admin'");
mysqli_query($conn, "UPDATE users SET password = '$student_hash' WHERE username = 'student1'");

echo "<h2>Passwords successfully updated!</h2>";
echo "<a href='login.php'>Click here to Login</a>";
?>