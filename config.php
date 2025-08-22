<?php
$dsn = 'mysql:host=127.0.0.1;dbname=student_study_planner;charset=utf8mb4';
$user = 'root';   // XAMPP default
$pass = '';       // XAMPP default

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
