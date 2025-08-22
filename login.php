<?php
require 'config.php';
session_start(); // needed for login sessions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash, name FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // store user ID and name in session
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
<html>
<head>
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body class="container py-5">

<h2 class="mb-4">Login</h2>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" class="card p-4" autocomplete="off">
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required autocomplete="off">
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required autocomplete="off">
  </div>
  <button class="btn btn-success">Login</button>
</form>
<script src="js/script.js"></script>

</body>
</html>
