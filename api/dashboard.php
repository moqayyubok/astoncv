<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// SECURITY: Authorization — isLoggedIn() checks $_SESSION['user_id']; unauthenticated
// users are redirected to login and cannot access any dashboard content.
if (!isLoggedIn()) {
    redirect('/login.php');
}

$user  = null;
$error = '';

try {
    $pdo  = getDB();
    // SECURITY: Prepared statements — parameterised query prevents SQL injection.
    $stmt = $pdo->prepare('SELECT * FROM cvs WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $error = 'Could not load your profile.';
}

$tags = $user ? array_filter(array_map('trim', explode(',', $user['keyprogramming'] ?? ''))) : [];
?>

<div class="page-heading">
    <h1>Welcome back, <?= escape($_SESSION['user_name']) ?></h1>
    <p>Your CV profile on AstonCV.</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php endif; ?>

<?php if ($user): ?>
<div class="card">
    <table style="width:100%;border-collapse:collapse;font-size:.95rem;">
        <tbody>
            <tr>
                <th style="text-align:left;padding:.6rem .5rem;width:180px;color:#555;">Name</th>
                <td style="padding:.6rem .5rem;"><?= escape($user['name']) ?></td>
            </tr>
            <tr style="background:#f9fafb;">
                <th style="text-align:left;padding:.6rem .5rem;color:#555;">Email</th>
                <td style="padding:.6rem .5rem;"><?= escape($user['email']) ?></td>
            </tr>
            <tr>
                <th style="text-align:left;padding:.6rem .5rem;color:#555;">Languages</th>
                <td style="padding:.6rem .5rem;">
                    <div class="tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag"><?= escape($tag) ?></span>
                        <?php endforeach; ?>
                        <?php if (empty($tags)): ?>
                            <span style="color:#aaa;">—</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr style="background:#f9fafb;">
                <th style="text-align:left;padding:.6rem .5rem;color:#555;vertical-align:top;">Profile</th>
                <td style="padding:.6rem .5rem;line-height:1.6;">
                    <?= $user['profile'] ? escape($user['profile']) : '<span style="color:#aaa;">—</span>' ?>
                </td>
            </tr>
            <tr>
                <th style="text-align:left;padding:.6rem .5rem;color:#555;vertical-align:top;">Education</th>
                <td style="padding:.6rem .5rem;line-height:1.6;">
                    <?= $user['education'] ? escape($user['education']) : '<span style="color:#aaa;">—</span>' ?>
                </td>
            </tr>
            <tr style="background:#f9fafb;">
                <th style="text-align:left;padding:.6rem .5rem;color:#555;">Links</th>
                <td style="padding:.6rem .5rem;">
                    <?php if ($user['URLlinks']): ?>
                        <?php foreach (array_filter(array_map('trim', explode(',', $user['URLlinks']))) as $link): ?>
                            <a href="<?= escape($link) ?>" target="_blank" rel="noopener"><?= escape($link) ?></a><br>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color:#aaa;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top:1rem;display:flex;gap:1rem;">
    <a href="/search.php" class="btn btn-secondary">Browse CVs</a>
    <a href="/logout.php" class="btn btn-outline">Logout</a>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
