<?php
declare(strict_types=1);

// 1. Load environment and dependencies
require 'bootstrap.php';
require_once VENDOR_PATH . 'autoload.php';
require_once UTILS_PATH . 'envSetter.util.php';

// 2. Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['dbname']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// 3. Load users from static data
$users = require_once DUMMIES_PATH . 'users.staticData.php';
if (!$users || !is_array($users)) {
    // Log error instead of printing directly
    error_log("❌ No users loaded from staticData.");
    return;
}

try {
    // 4. Start a transaction
    $pdo->beginTransaction();

    // 5. Clear the users table
    $pdo->exec('TRUNCATE TABLE public."users" RESTART IDENTITY CASCADE');

    // 6. Prepare insert statement
    $stmt = $pdo->prepare("
        INSERT INTO public.\"users\" (username, role, first_name, last_name, password)
        VALUES (:username, :role, :fn, :ln, :pw)
    ");

    // 7. Insert each user
    foreach ($users as $u) {
        $stmt->execute([
            ':username' => $u['username'],
            ':role' => $u['role'],
            ':fn' => $u['first_name'],
            ':ln' => $u['last_name'],
            ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
        ]);
    }

    // 8. Commit the transaction
    $pdo->commit();
    $GLOBALS['seederStatus'] = "✅ PostgreSQL seeding complete!";

} catch (PDOException $e) {
    $pdo->rollBack(); // Rollback on error
    error_log("❌ DB Error during seeding: " . $e->getMessage());
}