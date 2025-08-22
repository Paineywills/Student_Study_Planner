<?php
require 'config.php';
session_start();

// force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// fetch the task
if (!isset($_GET['id'])) {
    header("Location: tasks.php");
    exit;
}

$task_id = (int)$_GET['id'];

// get task details
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id=? AND user_id=?");
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    die("❌ Task not found or no permission.");
}

// handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'] ?? null;
    $priority = $_POST['priority'] ?? 'Medium';

    if ($title) {
        $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, due_date=?, priority=? WHERE id=? AND user_id=?");
        $stmt->execute([$title, $description, $due_date, $priority, $task_id, $user_id]);
        header("Location: tasks.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Task</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2>Edit Task</h2>

<form method="post" class="card p-4">
  <div class="mb-3">
    <label>Task Title</label>
    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
  </div>
  <div class="mb-3">
    <label>Due Date</label>
    <input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>">
  </div>
  <div class="mb-3">
    <label>Priority</label>
    <select name="priority" class="form-control">
      <option value="High" <?= $task['priority']=='High'?'selected':'' ?>>High</option>
      <option value="Medium" <?= $task['priority']=='Medium'?'selected':'' ?>>Medium</option>
      <option value="Low" <?= $task['priority']=='Low'?'selected':'' ?>>Low</option>
    </select>
  </div>
  <button class="btn btn-primary">Update Task</button>
  <a href="tasks.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
