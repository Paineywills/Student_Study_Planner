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
            $success = "Registered successfully! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            $error = "This email is already registered.";
        }
    } else {
        $error = "Please enter valid info (password must be at least 8 characters).";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body class="container py-5">

<h2 class="mb-4">Register</h2>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= $success ?></div>
<?php elseif (!empty($error)): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" class="card p-4">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-primary">Register</button>
</form>
<script src="js/script.js"></script>

</body>
</html>
