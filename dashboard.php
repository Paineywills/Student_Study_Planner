<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="dashboard container">
  
  <!-- Header -->
  <header class="mb-4 text-center">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 🎉</h2>
    <p class="subtitle">Here’s an overview of your progress</p>
  </header>

  <!-- Stats Section -->
  <section class="stats-box">
    <div class="stats-card">
      <h3>Total Tasks</h3>
      <p>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=?");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
    <div class="stats-card">
      <h3>Completed</h3>
      <p>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='done'");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
    <div class="stats-card">
      <h3>Pending</h3>
      <p>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='pending'");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
  </section>

  <!-- Action Buttons -->
  <section class="actions my-4 text-center">
    <a href="tasks.php" class="btn btn-primary">Manage My Tasks</a>
    <a href="add_task.php" class="btn btn-success">Add New Task</a>
    <a href="profile.php" class="btn btn-info">My Profile</a>
  </section>

</div>

<!-- Logout fixed -->
<a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

<script src="js/script.js"></script>
</body>
</html>
