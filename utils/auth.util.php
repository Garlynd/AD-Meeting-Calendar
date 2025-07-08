<?php
// filepath: utils/auth.util.php

class Auth
{
    // Ensure session is started
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Login logic: returns true on success, false on failure
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        self::init();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'full_name' => $user['full_name'] ?? null
            ];
            return true;
        }
        return false;
    }

    // Get current user from session
    public static function user()
    {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    // Check if user is logged in
    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user']);
    }

    // Logout: clear session and cookies
    public static function logout()
    {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}