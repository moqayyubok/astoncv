<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/../setup.sql');

    // Strip SQL comments and split by semicolon
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => $s !== '' && !preg_match('/^\s*--/', $s)
    );

    foreach ($statements as $i => $stmt) {
        // Skip pure-comment blocks
        $clean = preg_replace('/--[^\n]*/', '', $stmt);
        if (trim($clean) === '') {
            continue;
        }

        try {
            $pdo->exec($stmt);
            echo "OK  [" . ($i + 1) . "] " . substr(trim($stmt), 0, 80) . "\n";
        } catch (PDOException $e) {
            echo "ERR [" . ($i + 1) . "] " . substr(trim($stmt), 0, 80) . "\n";
            echo "    => " . $e->getMessage() . "\n";
        }
    }

    echo "\nDone.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
