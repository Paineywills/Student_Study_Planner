<?php
require 'config.php';
session_start();

// force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: tasks.php");
    exit;
}

$task_id = (int)$_GET['id'];

// delete task
$stmt = $pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
$stmt->execute([$task_id, $user_id]);

header("Location: tasks.php");
exit;
