<?php
declare(strict_types=1);


require_once 'bootstrap.php';

require_once rtrim(VENDOR_PATH, '/') . '/autoload.php';

require_once rtrim(UTILS_PATH, '/') . '/envSetter.util.php';



$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Drop old tables
echo "Dropping old tables…\n";
foreach ([
    'meeting_users',
    'tasks',
    'meetings',
    'users',
    'projects'
] as $table) {
    // Use IF EXISTS so it won’t error if the table is already gone
    $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
}

// Apply schema files
$schemaFiles = [
    'database/users.model.sql',
    'database/meetings.model.sql',
    'database/meeting_users.model.sql',
    'database/tasks.model.sql'
];

foreach ($schemaFiles as $file) {
    echo "Applying schema from $file…\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("Could not read $file");
    } else {
        echo "Creation Success from $file\n";
    }
    $pdo->exec($sql);
}

echo "✅ Database migration complete!\n";