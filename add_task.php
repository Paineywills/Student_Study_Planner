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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Add a New Task</h2>
    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Normal Task Form -->
    <form method="post">
        <input type="text" name="task_title" placeholder="Task Title" required><br>
        <textarea name="task_description" placeholder="Task Description"></textarea><br>
        <select name="task_priority">
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
        </select><br>
        <button type="submit">Add Task</button>
    </form>

    <h3>Quick Add Tasks</h3>
    <?php foreach ($popular_tasks as $task): ?>
        <form method="post" style="display:inline;">
            <input type="hidden" name="task_title" value="<?php echo $task['title']; ?>">
            <input type="hidden" name="task_description" value="<?php echo $task['description']; ?>">
            <input type="hidden" name="task_priority" value="<?php echo $task['priority']; ?>">
            <button type="submit"><?php echo $task['title']; ?></button>
        </form>
    <?php endforeach; ?>
</body>
</html>
