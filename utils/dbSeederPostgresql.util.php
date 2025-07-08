<?php
declare(strict_types=1);


require_once 'bootstrap.php';

require VENDOR_PATH . 'autoload.php';

require_once UTILS_PATH . 'envSetter.util.php';

// Load dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';

// Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Apply schema files
echo "Working on schema\n";
$schemaFiles = [
    'database/users.model.sql',
    'database/meetings.model.sql',
    'database/meeting_users.model.sql',
    'database/tasks.model.sql'
];

foreach ($schemaFiles as $file) {
    echo "✅Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

// Truncate tables before seeding
echo "✅Truncating tables…\n";
$tables = ['meeting_users', 'tasks', 'meetings', 'users'];
foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

// Seeding users
echo "Seeding users…\n";
$stmt = $pdo->prepare("
    INSERT INTO users (username, role, full_name, password)
    VALUES (:username, :role, :full_name, :pw)
");

foreach ($users as $u) {
    $full_name = $u['first_name'] . ' ' . $u['last_name'];
    $stmt->execute([
        ':username' => $u['username'],
        ':role' => $u['role'],
        ':full_name' => $full_name,
        ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
    ]);
}

echo "✅ PostgreSQL seeding complete!\n";