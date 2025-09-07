<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash, name FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MyApp | Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="container py-5">

  <div class="text-center mb-5">
    <h1 class="fw-bold">Student Planner</h1>
    <p class="text-muted">Sign in to continue</p>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="card p-4 mx-auto" style="max-width: 400px;" autocomplete="off">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input id="password" type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-success w-100">Login</button>

    <div class="mt-3 text-center">
      <a href="forgot_password.php" class="d-block">Forgot password?</a>
      <a href="register.php" class="d-block">Don’t have an account? Register</a>
    </div>
  </form>

  <script src="js/script.js"></script>
</body>
</html>
