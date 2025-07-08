<?php
require_once 'bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::init();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is already logged in
if (Auth::check()) {
    header('Location: /pages/Home/index.php');
    exit;
}

$error = trim((string) ($_GET['error'] ?? ''));
$error = str_replace("%", " ", $error);

// Set up layout variables for login page
$title = 'Login - Meeting Calendar';

// Content for the login form
ob_start();
?>
<div class="login-container">
    <form action="/handlers/auth.handler.php" method="POST">
        <label for="username" class="label">Username</label>
        <input id="username" name="username" type="text" required class="input">

        <label for="password" class="label">Password</label>
        <input id="password" name="password" type="password" required class="input">

        <input type="hidden" name="action" value="login">
        <button type="submit" class="button">Log In</button>

        <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
</div>
<?php
$content = ob_get_clean();

// For login page, we'll use a simple layout without navbar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?= $content ?>
</body>
</html>