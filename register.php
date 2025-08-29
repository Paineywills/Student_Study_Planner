<?php
require 'config.php'; // include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 8) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
        try {
            $stmt->execute([$name, $email, $hash]);
            $success = "Registered successfully! You can now <a href='index.php'>login</a>.";
        } catch (PDOException $e) {
            $error = "This email is already registered.";
        }
    } else {
        $error = "Please enter valid info (password must be at least 8 characters).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MyApp | Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="container py-5">

  <div class="text-center mb-5">
    <h1 class="fw-bold">Student Planner</h1>
    <p class="text-muted">Create your account</p>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="card p-4 mx-auto" style="max-width: 400px;" autocomplete="off">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input id="name" type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input id="password" type="password" name="password" class="form-control" required minlength="8">
    </div>
    <button class="btn btn-primary w-100">Register</button>

    <div class="mt-3 text-center">
      <a href="index.php">Already have an account? Login</a>
    </div>
  </form>

  <script src="js/script.js"></script>
</body>
</html>
