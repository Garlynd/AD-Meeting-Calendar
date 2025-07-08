<?php
require_once '../../utils/auth.util.php';
Auth::init();
if (Auth::check()) {
    header("Location: /dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Meeting Calendar</title>
  <link rel="stylesheet" href="assets/css/login.css"> <!-- External CSS linked here -->
</head>
<body>

  <form class="login-container" action="/handlers/auth.handler.php" method="POST">
    <h2>Login</h2>

    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>

    <button class="btn" type="submit">Login</button>

    <a href="/index.php" class="btn2">← Back to Home</a>

    <?php if (isset($_GET['error'])): ?>
      <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
  </form>

</body>
</html>