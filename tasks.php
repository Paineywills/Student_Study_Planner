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

    if ($title !== '') {
        $priority = $_POST['priority'] ?? 'Medium';
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority, status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$user_id, $title, $description, $due_date, $priority, 'pending']);
        header("Location: tasks.php");
        exit;
    }
}

// Mark task as done
if (isset($_GET['done'])) {
    $task_id = (int) $_GET['done'];
    $stmt = $pdo->prepare("UPDATE tasks SET status='done' WHERE id=? AND user_id=?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php");
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

if (!empty($_GET['status']) && in_array($_GET['status'], ['pending','done'], true)) {
    $where .= " AND status=?";
    $params[] = $_GET['status'];
}

if (!empty($_GET['priority']) && in_array($_GET['priority'], ['High','Medium','Low'], true)) {
    $where .= " AND priority=?";
    $params[] = $_GET['priority'];
}

$order = "due_date ASC";
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] === 'priority') {
        $order = "FIELD(priority, 'High','Medium','Low')";
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
  <meta charset="utf-8">
  <title>My Tasks</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="tasks-body">
  <div class="container py-5">

    <h2 class="mb-4 text-center">My Study Tasks</h2>

    <!-- Add Task Form -->
    <div class="card soft-card mb-4">
      <div class="card-body">
        <form method="post" class="row g-3">
          <div class="col-12">
            <label class="form-label">Task Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
              <option value="High">High</option>
              <option value="Medium" selected>Medium</option>
              <option value="Low">Low</option>
            </select>
          </div>
          <div class="col-12">
            <button class="btn btn-primary w-100">Add Task</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Filters -->
    <div class="card soft-card mb-4">
      <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="">All</option>
              <option value="pending" <?= (($_GET['status'] ?? '')==='pending')?'selected':'' ?>>Pending</option>
              <option value="done" <?= (($_GET['status'] ?? '')==='done')?'selected':'' ?>>Done</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
              <option value="">All</option>
              <option value="High" <?= (($_GET['priority'] ?? '')==='High')?'selected':'' ?>>High</option>
              <option value="Medium" <?= (($_GET['priority'] ?? '')==='Medium')?'selected':'' ?>>Medium</option>
              <option value="Low" <?= (($_GET['priority'] ?? '')==='Low')?'selected':'' ?>>Low</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Sort By</label>
            <select name="sort" class="form-select">
              <option value="">Default</option>
              <option value="due_date" <?= (($_GET['sort'] ?? '')==='due_date')?'selected':'' ?>>Due Date</option>
              <option value="priority" <?= (($_GET['sort'] ?? '')==='priority')?'selected':'' ?>>Priority</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill">Apply</button>
            <a href="tasks.php" class="btn btn-outline-secondary flex-fill">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Bulk actions -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
      <a href="?action=mark_all_done" class="btn btn-success">Mark All as Done</a>
      <a href="?action=delete_all" class="btn btn-danger"
         onclick="return confirm('Are you sure you want to delete ALL tasks?');">Delete All Tasks</a>
    </div>

    <!-- Task Table -->
    <div class="card soft-card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="min-width:160px;">Title</th>
                <th style="min-width:240px;">Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Priority</th>
                <th style="min-width:230px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($tasks)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No tasks found.</td></tr>
              <?php else: ?>
                <?php foreach ($tasks as $t): ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($t['title']) ?></td>
                  <td class="text-wrap"><?= nl2br(htmlspecialchars($t['description'])) ?></td>
                  <td><?= htmlspecialchars($t['due_date']) ?></td>
                  <td>
                    <span class="badge rounded-pill <?= $t['status']==='done' ? 'bg-success' : 'bg-warning text-dark' ?>">
                      <?= htmlspecialchars($t['status']) ?>
                    </span>
                  </td>
                  <td>
                    <?php
                      $p = htmlspecialchars($t['priority']);
                      $pc = $p==='High' ? 'bg-danger' : ($p==='Medium' ? 'bg-primary' : 'bg-secondary');
                    ?>
                    <span class="badge rounded-pill <?= $pc ?>"><?= $p ?></span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm flex-wrap action-buttons" role="group" aria-label="Actions">
                      <?php if ($t['status'] !== 'done'): ?>
                        <a href="?done=<?= $t['id'] ?>" class="btn btn-success">Mark Done</a>
                      <?php endif; ?>
                      <a href="edit_task.php?id=<?= $t['id'] ?>" class="btn btn-warning">Edit</a>
                      <a href="delete_task.php?id=<?= $t['id'] ?>" class="btn btn-danger"
                         onclick="return confirm('Delete this task?');">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Back button -->
    <div class="mt-4 text-center">
      <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
