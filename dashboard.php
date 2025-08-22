<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body class="container py-5">

<h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 🎉</h2>

<!-- Quick Stats Section -->
<div class="row my-4">
  <div class="col-md-4">
    <div class="card text-center p-3">
      <h5>Total Tasks</h5>
      <p>
        <?php
        require 'config.php';
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=?");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center p-3">
      <h5>Completed Tasks</h5>
      <p>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='done'");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center p-3">
      <h5>Pending Tasks</h5>
      <p>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='pending'");
        $stmt->execute([$_SESSION['user_id']]);
        echo $stmt->fetchColumn();
        ?>
      </p>
    </div>
  </div>
</div>

<!-- Navigation Links -->
<div class="mb-4">
  <a href="tasks.php" class="btn btn-primary me-2">Manage My Tasks</a>
<a href="add_task.php" class="btn btn-success me-2">Add New Task</a>

  <a href="profile.php" class="btn btn-info me-2">My Profile</a>
  <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
<script src="js/script.js"></script>


</body>
</html>
