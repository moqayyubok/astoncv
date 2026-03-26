<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

$pdo = getDB();
$userId = $_SESSION['user_id'];
$success = '';
$errors = [];

// Handle POST — save edits
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $keyprogramming = trim($_POST['keyprogramming'] ?? '');
    $profile = trim($_POST['profile'] ?? '');
    $education = trim($_POST['education'] ?? '');
    $urllinks = trim($_POST['URLlinks'] ?? '');

    // Validate
    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (strlen($name) > 100) {
        $errors[] = 'Name must be under 100 characters.';
    }
    if (strlen($keyprogramming) > 255) {
        $errors[] = 'Key programming languages must be under 255 characters.';
    }
    if (strlen($profile) > 500) {
        $errors[] = 'Profile must be under 500 characters.';
    }
    if (strlen($education) > 500) {
        $errors[] = 'Education must be under 500 characters.';
    }
    if (strlen($urllinks) > 500) {
        $errors[] = 'URL links must be under 500 characters.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('UPDATE cvs SET name = :name, keyprogramming = :keyprogramming, profile = :profile, education = :education, URLlinks = :urllinks WHERE id = :id');
            $stmt->execute([
                ':name' => $name,
                ':keyprogramming' => $keyprogramming,
                ':profile' => $profile,
                ':education' => $education,
                ':urllinks' => $urllinks,
                ':id' => $userId
            ]);
            $success = 'Your CV has been updated successfully.';
            // Update session name in case they changed it
            $_SESSION['user_name'] = $name;
        } catch (\PDOException $e) {
            error_log('Dashboard update error: ' . $e->getMessage());
            $errors[] = 'Could not update your CV. Please try again.';
        }
    }
}

// Fetch current data (always, including after POST so form shows updated values)
try {
    $stmt = $pdo->prepare('SELECT * FROM cvs WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch();
} catch (\PDOException $e) {
    error_log('Dashboard fetch error: ' . $e->getMessage());
    $user = null;
}

if (!$user) {
    echo '<div class="container"><div class="alert alert-error">Could not load your profile.</div></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<div class="container">
    <h1>My Dashboard</h1>
    <p>Update your CV details below.</p>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= escape($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <p><?= escape($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="/dashboard.php">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?= escape($user['name']) ?>" required maxlength="100">
            </div>

            <div class="form-group">
                <label>Email (cannot be changed)</label>
                <input type="email" value="<?= escape($user['email']) ?>" disabled readonly>
            </div>

            <div class="form-group">
                <label for="keyprogramming">Key Programming Languages</label>
                <input type="text" id="keyprogramming" name="keyprogramming" value="<?= escape($user['keyprogramming'] ?? '') ?>" maxlength="255" placeholder="e.g. PHP, Python, JavaScript">
            </div>

            <div class="form-group">
                <label for="profile">Profile Summary</label>
                <textarea id="profile" name="profile" maxlength="500" rows="4"><?= escape($user['profile'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="education">Education</label>
                <textarea id="education" name="education" maxlength="500" rows="3"><?= escape($user['education'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="URLlinks">Links (GitHub, LinkedIn, portfolio)</label>
                <textarea id="URLlinks" name="URLlinks" maxlength="500" rows="3"><?= escape($user['URLlinks'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
