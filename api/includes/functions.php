<?php

require_once __DIR__ . '/../config/database.php';

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';port='      . DB_PORT
             . ';dbname='    . DB_NAME
             . ';charset='   . DB_CHARSET;

        // Use real prepared statements and disable SSL cert verification for Railway proxy
        $options = [
            PDO::ATTR_ERRMODE                      => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE           => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES             => false,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA                 => '',
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

// Escape output to prevent XSS — use on every user value echoed to the page
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Generate a random CSRF token and store it in the session
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Check that the submitted CSRF token matches the one in the session
function csrfVerify(): void {
    $submitted = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrfToken(), $submitted)) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }
}
