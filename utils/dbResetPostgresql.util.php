<?php
declare(strict_types=1);

require_once 'bootstrap.php';

require VENDOR_PATH . 'autoload.php';

require_once UTILS_PATH . 'envSetter.util.php';


$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

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

echo "✅Truncating tables…\n";
$tables = ['meeting_users', 'tasks', 'meetings', 'users'];
foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

echo "✅Database reset and schema applied successfully.\n";