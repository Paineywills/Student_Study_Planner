<?php
require 'config.php';
session_start();

// force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Example: top 5 popular tasks (replace with DB query if you have a table)
$popular_tasks = [
    ['title'=>'Read Chapter 1', 'description'=>'Introduction to subject', 'priority'=>'Medium'],
    ['title'=>'Review Notes', 'description'=>'Go through last lecture notes', 'priority'=>'High'],
    ['title'=>'Practice Problems', 'description'=>'Solve 10 exercises', 'priority'=>'High'],
    ['title'=>'Flashcards', 'description'=>'Make flashcards for key terms', 'priority'=>'Low'],
    ['title'=>'Group Study', 'description'=>'Study with classmates for 1 hour', 'priority'=>'Medium'],
];

// Handle form submission
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tasks'])) {
    foreach ($_POST['tasks'] as $index) {
        $task = $popular_tasks[$index];
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority) VALUES (?,?,?,?,?)");
        $stmt->execute([$user_id, $task['title'], $task['description'], null, $task['priority']]);
    }
    $success = "Selected tasks added successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quick Add Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
<h2 class="mb-4">Quick Add Tasks</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">
    <div class="card p-4 mb-4">
        <?php foreach ($popular_tasks as $i => $task): ?>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="tasks[]" value="<?= $i ?>" id="task<?= $i ?>">
                <label class="form-check-label" for="task<?= $i ?>">
                    <strong><?= htmlspecialchars($task['title']) ?></strong> 
                    (<?= htmlspecialchars($task['priority']) ?>) – <?= htmlspecialchars($task['description']) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="btn btn-success">Add Selected Tasks</button>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</form>
</body>
</html>
