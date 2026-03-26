<?php
$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password'] ?? '';

    // Check both fields are filled before hitting the database
    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare('SELECT id, name, password FROM cvs WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Verify the password against the stored bcrypt hash
            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID on login to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                session_write_close();
                header('Location: /dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Login failed. Please try again.';
        }
    }
}
?>

<div class="form-card">
    <div class="page-heading">
        <h1>Log In</h1>
        <p>Access your AstonCV profile.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login.php" class="card">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= escape($email) ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-submit">
            <button type="submit" class="btn btn-primary">Log In</button>
        </div>
    </form>

    <p style="text-align:center;margin-top:.75rem;color:#a1a1aa;">
        No account yet? <a href="/register.php">Register here</a>
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
