<?php

require_once __DIR__ . '/../config/database.php';

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';port='      . DB_PORT
             . ';dbname='    . DB_NAME
             . ';charset='   . DB_CHARSET;

        // SECURITY: Prepared statements — PDO with ERRMODE_EXCEPTION and emulate_prepares=false
        // forces real prepared statements, preventing SQL injection.
        $options = [
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES         => false,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA                => '',
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}

function isLoggedIn(): bool {
    // SECURITY: Authorization — checks $_SESSION['user_id'] is set before granting access.
    return isset($_SESSION['user_id']);
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

// SECURITY: XSS prevention — wraps htmlspecialchars with ENT_QUOTES and UTF-8 encoding.
// Every user-supplied value echoed to the page must pass through escape().
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// SECURITY: CSRF tokens — generates a cryptographically random token stored in the session.
// Call csrfToken() to get (or create) the token; embed it as a hidden field in every POST form.
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// SECURITY: CSRF tokens — validates the submitted token against the session token.
// Call csrfVerify() at the top of every POST handler; it exits with 403 on mismatch.
function csrfVerify(): void {
    $submitted = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrfToken(), $submitted)) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }
}
