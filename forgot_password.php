<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // TODO: Generate reset token, store in DB, and send email
        $success = "If an account exists with that email, a reset link has been sent.";
    } else {
        // Avoid exposing which emails exist
        $success = "If an account exists with that email, a reset link has been sent.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MyApp | Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="container py-5">

  <div class="text-center mb-5">
    <h1 class="fw-bold">Student Planner</h1>
    <p class="text-muted">Reset your password</p>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post" class="card p-4 mx-auto" style="max-width: 400px;" autocomplete="off">
    <div class="mb-3">
      <label for="email" class="form-label">Enter your email</label>
      <input id="email" type="email" name="email" class="form-control" required>
    </div>
    <button class="btn btn-warning w-100">Send Reset Link</button>
    <div class="mt-3 text-center">
      <a href="index.php">Back to login</a>
    </div>
  </form>

</body>
</html>
