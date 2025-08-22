<?php
require 'config.php';
session_start();

// Force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user info
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($name && $email) {
        // Check password if updating
        if ($password) {
            if ($password !== $password_confirm) {
                $error = "Passwords do not match.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password_hash=? WHERE id=?");
                $stmt->execute([$name, $email, $password_hash, $user_id]);
                $success = "Profile updated successfully.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $stmt->execute([$name, $email, $user_id]);
            $success = "Profile updated successfully.";
        }
        // Update session name
        $_SESSION['user_name'] = $name;
    } else {
        $error = "Name and email cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2 class="mb-4">My Profile</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" class="card p-4">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
  </div>
  <div class="mb-3">
    <label>New Password (leave blank to keep current)</label>
    <input type="password" name="password" class="form-control" autocomplete="off">
  </div>
  <div class="mb-3">
    <label>Confirm New Password</label>
    <input type="password" name="password_confirm" class="form-control" autocomplete="off">
  </div>
  <button class="btn btn-success">Update Profile</button>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</form>

</body>
</html>
