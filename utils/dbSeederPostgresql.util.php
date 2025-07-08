<?php
declare(strict_types=1);

require_once 'bootstrap.php';
require VENDOR_PATH . 'autoload.php';
require_once UTILS_PATH . 'envSetter.util.php';

// Load dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';

// Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Database connection successful.\n";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage() . "\n");
}

// Apply schema
echo "Working on schema\n";
$schemaFiles = [
    'database/users.model.sql',
    'database/meetings.model.sql',
    'database/meeting_users.model.sql',
    'database/tasks.model.sql'
];

foreach ($schemaFiles as $file) {
    echo "✅ Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

// Truncate tables before seeding
echo "✅ Truncating tables…\n";
$tables = ['meeting_users', 'tasks', 'meetings', 'users'];
foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

// --- SEEDING LOGIC ---

// Seed users
echo "Seeding users…\n";
$stmt = $pdo->prepare("
    INSERT INTO users (username, role, first_name, last_name, password)
    VALUES (:username, :role, :fn, :ln, :pw)
");
foreach ($users as $u) {
    $stmt->execute([
        ':username' => $u['username'],
        ':role' => $u['role'],
        ':fn' => $u['first_name'],
        ':ln' => $u['last_name'],
        ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
    ]);
}

echo "✅ PostgreSQL seeding complete!\n";