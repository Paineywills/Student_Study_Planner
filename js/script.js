// script.js

// Mark a single task as done (AJAX request)
function markTaskDone(taskId) {
    if (!confirm("Mark this task as done?")) return;

    fetch(`tasks.php?done=${taskId}`, {
        method: "GET"
    })
    .then(response => {
        if (response.ok) {
            // Reload only the tasks list
            location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

// Delete a single task
function deleteTask(taskId) {
    if (!confirm("Delete this task?")) return;

    fetch(`delete_task.php?id=${taskId}`, {
        method: "GET"
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

// Mark all as done
function markAllDone() {
    if (!confirm("Mark ALL tasks as done?")) return;

    fetch(`tasks.php?action=mark_all_done`)
    .then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}

// Delete all tasks
function deleteAllTasks() {
    if (!confirm("Delete ALL tasks?")) return;

    fetch(`tasks.php?action=delete_all`)
    .then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}
function markAllDone() {
    if (confirm("Mark all tasks as done?")) {
        fetch("tasks.php?action=mark_all_done")
            .then(() => location.reload());
    }
}

function deleteAllTasks() {
    if (confirm("Are you sure you want to delete ALL tasks?")) {
        fetch("tasks.php?action=delete_all")
            .then(() => location.reload());
    }
}
