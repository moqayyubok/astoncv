<?php
$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$errors  = [];
$success = '';
$fields  = ['name' => '', 'email' => '', 'keyprogramming' => '', 'profile' => '', 'education' => '', 'URLlinks' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $key => $_) {
        $fields[$key] = trim($_POST[$key] ?? '');
    }
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Validate all required fields
    if ($fields['name'] === '') {
        $errors[] = 'Full name is required.';
    }
    if ($fields['email'] === '' || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                'INSERT INTO cvs (name, email, password, keyprogramming, profile, education, URLlinks)
                 VALUES (:name, :email, :password, :keyprogramming, :profile, :education, :URLlinks)'
            );
            $stmt->execute([
                ':name'           => $fields['name'],
                ':email'          => $fields['email'],
                // Hash the password with bcrypt before storing it
                ':password'       => password_hash($password, PASSWORD_BCRYPT),
                ':keyprogramming' => $fields['keyprogramming'],
                ':profile'        => $fields['profile'],
                ':education'      => $fields['education'],
                ':URLlinks'       => $fields['URLlinks'],
            ]);

            $success = 'Account created! <a href="/login.php">Log in now</a>.';
            $fields  = array_fill_keys(array_keys($fields), '');
        } catch (PDOException $e) {
            if ((int)$e->getCode() === 23000) {
                $errors[] = 'That email address is already registered.';
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<div class="form-card">
    <div class="page-heading">
        <h1>Create Your CV</h1>
        <p>Join the AstonCV developer database.</p>
    </div>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= escape($e) ?></div>
    <?php endforeach; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="/register.php" class="card">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="<?= escape($fields['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?= escape($fields['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password * <small>(min. 8 chars)</small></label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password2">Confirm Password *</label>
            <input type="password" id="password2" name="password2" required>
        </div>
        <div class="form-group">
            <label for="keyprogramming">Key Programming Languages <small>(comma-separated)</small></label>
            <input type="text" id="keyprogramming" name="keyprogramming" value="<?= escape($fields['keyprogramming']) ?>" placeholder="e.g. PHP, Python, JavaScript">
        </div>
        <div class="form-group">
            <label for="profile">Profile Summary</label>
            <textarea id="profile" name="profile"><?= escape($fields['profile']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="education">Education</label>
            <textarea id="education" name="education"><?= escape($fields['education']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="URLlinks">Links <small>(GitHub, LinkedIn, portfolio…)</small></label>
            <input type="text" id="URLlinks" name="URLlinks" value="<?= escape($fields['URLlinks']) ?>">
        </div>
        <div class="form-submit">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>

    <p style="text-align:center;margin-top:.75rem;color:#a1a1aa;">
        Already registered? <a href="/login.php">Log in</a>
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
