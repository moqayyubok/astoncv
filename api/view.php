<?php
require_once __DIR__ . '/includes/functions.php';

// Reject any non-integer or out-of-range id values
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false || $id === null) {
    redirect('/index.php');
}

$cv    = null;
$error = '';

try {
    $stmt = getDB()->prepare(
        'SELECT id, name, email, keyprogramming, profile, education, URLlinks
         FROM cvs
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $cv = $stmt->fetch();
} catch (PDOException $e) {
    $error = 'Database error. Please try again later.';
}

$pageTitle = $cv ? escape($cv['name']) . '\'s CV' : 'CV Not Found';
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
    <a href="/index.php" class="back-link" style="margin-bottom:0;">Back to all CVs</a>
    <?php if ($cv && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $cv['id']): ?>
        <a href="/dashboard.php" class="btn btn-primary">Edit my CV</a>
    <?php endif; ?>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>

<?php elseif ($cv === false): ?>
    <div class="alert alert-error">
        No CV found with that ID. It may have been removed.
    </div>

<?php else:
    $links = array_filter(array_map('trim', explode(',', $cv['URLlinks'] ?? '')));
?>
    <div class="page-heading">
        <h1><?= escape($cv['name']) ?></h1>
    </div>

    <div class="card cv-detail">
        <div class="field">
            <span class="field-label">Name</span>
            <span class="field-value"><?= escape($cv['name']) ?></span>
        </div>
        <div class="field">
            <span class="field-label">Email</span>
            <span class="field-value"><?= escape($cv['email']) ?></span>
        </div>
        <div class="field">
            <span class="field-label">Key Languages</span>
            <span class="field-value">
                <?php if ($cv['keyprogramming']): ?>
                    <div class="tags">
                    <?php foreach (array_filter(array_map('trim', explode(',', $cv['keyprogramming']))) as $tag): ?>
                        <span class="tag"><?= escape($tag) ?></span>
                    <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <span style="color:#52525b;">—</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="field">
            <span class="field-label">Profile</span>
            <span class="field-value">
                <?= $cv['profile'] ? escape($cv['profile']) : '<span style="color:#52525b;">—</span>' ?>
            </span>
        </div>
        <div class="field">
            <span class="field-label">Education</span>
            <span class="field-value">
                <?= $cv['education'] ? escape($cv['education']) : '<span style="color:#52525b;">—</span>' ?>
            </span>
        </div>
        <div class="field">
            <span class="field-label">Links</span>
            <span class="field-value">
                <?php if ($links): ?>
                    <?php foreach ($links as $link): ?>
                        <a href="<?= escape($link) ?>" target="_blank" rel="noopener noreferrer">
                            <?= escape($link) ?>
                        </a><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="color:#52525b;">—</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
