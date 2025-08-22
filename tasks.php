<?php
require 'config.php';
session_start();

// force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle new task form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'] ?? null;

    if ($title) {
        $priority = $_POST['priority'] ?? 'Medium';
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority, status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$user_id, $title, $description, $due_date, $priority, 'pending']);
    }
}

    // Mark task as done
if (isset($_GET['done'])) {
    $task_id = (int) $_GET['done'];
    $stmt = $pdo->prepare("UPDATE tasks SET status='done' WHERE id=? AND user_id=?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php"); // refresh to avoid resubmission
    exit;
}

    // Mark all as done
if (isset($_GET['action']) && $_GET['action'] === 'mark_all_done') {
    $stmt = $pdo->prepare("UPDATE tasks SET status='done' WHERE user_id=? AND status='pending'");
    $stmt->execute([$user_id]);
    header("Location: tasks.php");
    exit;
}

// Delete all tasks
if (isset($_GET['action']) && $_GET['action'] === 'delete_all') {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE user_id=?");
    $stmt->execute([$user_id]);
    header("Location: tasks.php");
    exit;
}



    // Fetch tasks with filters
$where = "user_id=?";
$params = [$user_id];

// filter by status
if (!empty($_GET['status']) && in_array($_GET['status'], ['pending','done'])) {
    $where .= " AND status=?";
    $params[] = $_GET['status'];
}

// filter by priority
if (!empty($_GET['priority']) && in_array($_GET['priority'], ['High','Medium','Low'])) {
    $where .= " AND priority=?";
    $params[] = $_GET['priority'];
}

// sorting
$order = "due_date ASC";
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] === 'priority') {
        $order = "FIELD(priority, 'High','Medium','Low')"; // custom priority order
    } elseif ($_GET['sort'] === 'due_date') {
        $order = "due_date ASC";
    }
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE $where ORDER BY $order");
$stmt->execute($params);
$tasks = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html>
<head>
  <title>My Tasks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2 class="mb-4">My Study Tasks</h2>

<!-- Task form -->
<form method="post" class="card p-4 mb-4">
  <div class="mb-3">
    <label>Task Title</label>
    <input type="text" name="title" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label>Due Date</label>
    <input type="date" name="due_date" class="form-control">
  </div>
  <button class="btn btn-primary">Add Task</button>

<div class="mb-3">
  <label>Priority</label>
  <select name="priority" class="form-control">
    <option value="High">High</option>
    <option value="Medium" selected>Medium</option>
    <option value="Low">Low</option>
  </select>
</div>


</form>

    <!-- Filters -->
<form method="get" class="row g-3 mb-4">
  <div class="col-md-3">
    <select name="status" class="form-control">
      <option value="">-- Filter by Status --</option>
      <option value="pending" <?= (($_GET['status'] ?? '')=='pending')?'selected':'' ?>>Pending</option>
      <option value="done" <?= (($_GET['status'] ?? '')=='done')?'selected':'' ?>>Done</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="priority" class="form-control">
      <option value="">-- Filter by Priority --</option>
      <option value="High" <?= (($_GET['priority'] ?? '')=='High')?'selected':'' ?>>High</option>
      <option value="Medium" <?= (($_GET['priority'] ?? '')=='Medium')?'selected':'' ?>>Medium</option>
      <option value="Low" <?= (($_GET['priority'] ?? '')=='Low')?'selected':'' ?>>Low</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="sort" class="form-control">
      <option value="">-- Sort By --</option>
      <option value="due_date" <?= (($_GET['sort'] ?? '')=='due_date')?'selected':'' ?>>Due Date</option>
      <option value="priority" <?= (($_GET['sort'] ?? '')=='priority')?'selected':'' ?>>Priority</option>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary">Apply</button>
    <a href="tasks.php" class="btn btn-secondary">Reset</a>
  </div>
</form>
    <div class="mb-3">
    <a href="tasks.php?action=mark_all_done" class="btn btn-success me-2"
       onclick="return confirm('Mark all tasks as done?');">Mark All as Done</a>
    <a href="tasks.php?action=delete_all" class="btn btn-danger"
       onclick="return confirm('Are you sure you want to delete all tasks?');">Delete All Tasks</a>
</div>


<!-- Task list -->
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Due Date</th>
      <th>Status</th>
      <th>Action</th>
      <th>Priority</th>

    </tr>
  </thead>
  <tbody>
    <?php foreach ($tasks as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t['title']) ?></td>
      <td><?= htmlspecialchars($t['description']) ?></td>
      <td><?= htmlspecialchars($t['due_date']) ?></td>
      <td><?= $t['status'] ?></td>
      <td>
       <?php if ($t['status'] !== 'done'): ?>
         <a href="?done=<?= $t['id'] ?>" class="btn btn-success btn-sm">Mark Done</a>
        <?php endif; ?>
        <a href="edit_task.php?id=<?= $t['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
       <a href="delete_task.php?id=<?= $t['id'] ?>" class="btn btn-danger btn-sm"
          onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
      </td>
      <td><?= $t['priority'] ?></td>
    </tr>

    <?php endforeach; ?>
  </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

</body>
</html>
