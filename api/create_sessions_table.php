<?php
// Run this once to create the sessions table in Railway MySQL.
// Visit /create_sessions_table.php in the browser, then delete or ignore the file.
require_once __DIR__ . '/includes/functions.php';

try {
    $pdo = getDB();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `sessions` (
          `id`            VARCHAR(128)   NOT NULL,
          `data`          TEXT           NOT NULL,
          `last_accessed` INT UNSIGNED   NOT NULL,
          PRIMARY KEY (`id`),
          INDEX (`last_accessed`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "OK — sessions table is ready.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
