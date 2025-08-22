<?php
$dsn = 'mysql:host=127.0.0.1;dbname=student_study_planner;charset=utf8mb4';
$user = 'root';   // default XAMPP username
$pass = '';       // default XAMPP password (empty)

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
