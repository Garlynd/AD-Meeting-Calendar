<?php
declare(strict_types=1);

require_once 'bootstrap.php';
require VENDOR_PATH . 'autoload.php';
require_once UTILS_PATH . 'envSetter.util.php';

if (!defined('DUMMIES_PATH')) {
    define('DUMMIES_PATH', __DIR__ . '/../staticData/dummies');
}

$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "Checking connection… OK\n";

$schemaFiles = [
    'database/users.model.sql',
    'database/meetings.model.sql',
    'database/meeting_users.model.sql',
    'database/tasks.model.sql'
];

echo "Applying schema files...\n";
foreach ($schemaFiles as $file) {
    echo "✅ Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

echo "✅ Truncating tables…\n";
$tables = ['meeting_users', 'tasks', 'meetings', 'users'];
foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

// Load users dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';

echo "Seeding users…\n";

$stmt = $pdo->prepare("
    INSERT INTO users (username, password, full_name, group_name, role)
    VALUES (:username, :pw, :full_name, :group_name, :role)
");

foreach ($users as $u) {
    // Compose full_name from first_name and last_name if available in dummy data
    $fullName = $u['first_name'] . ' ' . $u['last_name'];
    // group_name can be optional or null if not present in dummy data
    $groupName = $u['group_name'] ?? null;

    $stmt->execute([
        ':username' => $u['username'],
        ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
        ':full_name' => $fullName,
        ':group_name' => $groupName,
        ':role' => $u['role'],
    ]);
}

echo "✅ Seeding complete!\n";
