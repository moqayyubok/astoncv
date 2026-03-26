<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/../setup.sql');

    // Strip single-line comments first, then split by semicolon
    $sql        = preg_replace('/--[^\n]*/', '', $sql);
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => $s !== ''
    );

    foreach ($statements as $i => $stmt) {
        try {
            $pdo->exec($stmt);
            echo "OK  [" . ($i + 1) . "] " . substr($stmt, 0, 80) . "\n";
        } catch (PDOException $e) {
            echo "ERR [" . ($i + 1) . "] " . substr($stmt, 0, 80) . "\n";
            echo "    => " . $e->getMessage() . "\n";
        }
    }

    echo "\nDone.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
