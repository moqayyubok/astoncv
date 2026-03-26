<?php
$pageTitle = 'All CVs';
require_once __DIR__ . '/includes/header.php';

$cvs   = [];
$error = '';

try {
    $stmt = getDB()->query('SELECT id, name, email, keyprogramming FROM cvs ORDER BY name');
    $cvs  = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Could not load CVs. Please check the database connection.';
}
?>

<div class="page-heading">
    <h1>Programmer CVs</h1>
    <p>Browse every profile in the database.</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php else: ?>
    <table class="cv-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Key Programming Languages</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($cvs)): ?>
            <tr class="empty-row">
                <td colspan="3">No CVs found in the database yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($cvs as $cv): ?>
            <tr>
                <td>
                    <a href="/view.php?id=<?= (int)$cv['id'] ?>">
                        <?= escape($cv['name']) ?>
                    </a>
                </td>
                <td><?= escape($cv['email']) ?></td>
                <td><?= escape($cv['keyprogramming'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
