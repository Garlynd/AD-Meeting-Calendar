<?php
require_once '../utils/envSetter.util.php';
require_once '../utils/auth.util.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

// Use Auth logic from util
if (Auth::login($pdo, $username, $password)) {
    header("Location: /dashboard/index.php");
    exit;
} else {
    header("Location: /pages/login/index.php?error=invalid");
    exit;
}