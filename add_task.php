<?php
require 'config.php';
session_start();

// force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Expanded Quick Add Tasks (25 predefined tasks)
$popular_tasks = [
    ['title'=>'Read Chapter 1', 'description'=>'Introduction to subject', 'priority'=>'Medium'],
    ['title'=>'Review Notes', 'description'=>'Go through last lecture notes', 'priority'=>'High'],
    ['title'=>'Practice Problems', 'description'=>'Solve 10 exercises', 'priority'=>'High'],
    ['title'=>'Flashcards', 'description'=>'Make flashcards for key terms', 'priority'=>'Low'],
    ['title'=>'Group Study', 'description'=>'Study with classmates for 1 hour', 'priority'=>'Medium'],
    ['title'=>'Summarize Chapter', 'description'=>'Write a one-page summary of today\'s reading', 'priority'=>'Medium'],
    ['title'=>'Revise Past Papers', 'description'=>'Do 2 previous exam questions', 'priority'=>'High'],
    ['title'=>'Mind Map', 'description'=>'Create a mind map of key concepts', 'priority'=>'Low'],
    ['title'=>'Quiz Yourself', 'description'=>'Test yourself on key terms', 'priority'=>'Medium'],
    ['title'=>'Research Topic', 'description'=>'Find 3 articles online', 'priority'=>'Low'],
    ['title'=>'Homework Assignment', 'description'=>'Finish homework for tomorrow', 'priority'=>'High'],
    ['title'=>'Write Essay Draft', 'description'=>'Write introduction and outline', 'priority'=>'High'],
    ['title'=>'Check References', 'description'=>'Update bibliography list', 'priority'=>'Low'],
    ['title'=>'Lab Prep', 'description'=>'Read lab manual for tomorrow\'s experiment', 'priority'=>'Medium'],
    ['title'=>'Attend Lecture', 'description'=>'Watch recording or attend class', 'priority'=>'High'],
    ['title'=>'Organize Notes', 'description'=>'Reorder lecture notes by topic', 'priority'=>'Low'],
    ['title'=>'Create Study Plan', 'description'=>'Plan next 7 days of study tasks', 'priority'=>'Medium'],
    ['title'=>'Vocabulary Practice', 'description'=>'Learn 15 new words', 'priority'=>'Low'],
    ['title'=>'Presentation Prep', 'description'=>'Make 3 slides for presentation', 'priority'=>'Medium'],
    ['title'=>'Check Email', 'description'=>'Read and reply to study-related emails', 'priority'=>'Low'],
    ['title'=>'Peer Discussion', 'description'=>'Discuss tough concepts with a friend', 'priority'=>'Medium'],
    ['title'=>'Break & Stretch', 'description'=>'10-minute stretch and water break', 'priority'=>'Low'],
    ['title'=>'Meditation', 'description'=>'Do a 5-minute relaxation session', 'priority'=>'Low'],
    ['title'=>'Daily Journal', 'description'=>'Write what you learned today', 'priority'=>'Low'],
    ['title'=>'Set Goals', 'description'=>'Write 3 learning goals for tomorrow', 'priority'=>'Medium'],
];

// Handle form submission
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_title'])) {
        $title = $_POST['task_title'];
        $description = $_POST['task_description'] ?? '';
        $priority = $_POST['task_priority'] ?? 'Medium';

        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, priority) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $title, $description, $priority])) {
            $success = "Task added successfully!";
        } else {
            $success = "Error adding task.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card shadow-lg p-4">
                    <h2 class="mb-3">➕ Add a New Task</h2>

                    <?php if ($success): ?>
                        <div class="alert alert-info">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Normal Task Form -->
                    <form method="post" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" name="task_title" class="form-control" placeholder="Enter task title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="task_description" class="form-control" placeholder="Task details..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="task_priority" class="form-select">
                                <option>High</option>
                                <option>Medium</option>
                                <option>Low</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Task</button>
                    </form>
                </div>

                <!-- Quick Add Section -->
                <div class="card shadow-lg p-4 mt-4">
                    <h3 class="mb-3">⚡ Quick Add Tasks</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($popular_tasks as $task): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="task_title" value="<?php echo $task['title']; ?>">
                                <input type="hidden" name="task_description" value="<?php echo $task['description']; ?>">
                                <input type="hidden" name="task_priority" value="<?php echo $task['priority']; ?>">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <?php echo $task['title']; ?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Back button -->
                <div class="mt-4 text-center">
                    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
