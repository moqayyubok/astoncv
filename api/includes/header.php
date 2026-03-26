<?php
if (session_status() === PHP_SESSION_NONE) {
    // Set httpOnly and SameSite=Strict so the cookie can't be read by JS or sent cross-site
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? escape($pageTitle) . ' — AstonCV' : 'AstonCV' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <a href="/index.php" class="logo">AstonCV</a>
        <nav class="nav">
            <a href="/index.php">Home</a>
            <a href="/search.php">Search CVs</a>
            <?php if (isLoggedIn()): ?>
                <a href="/dashboard.php">My Dashboard</a>
                <span class="nav-user"><?= escape($_SESSION['user_name']) ?></span>
                <a href="/logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="/register.php">Register</a>
                <a href="/login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
