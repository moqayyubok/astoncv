<?php
$pageTitle = 'Search CVs';
require_once __DIR__ . '/includes/header.php';

$query = trim($_GET['q'] ?? '');
$cvs   = [];
$error = '';

try {
    $pdo = getDB();

    if ($query !== '') {
        // SECURITY: Prepared statements — user search term bound as a parameter, not concatenated.
        $stmt = $pdo->prepare(
            'SELECT id, name, email, keyprogramming
             FROM cvs
             WHERE name LIKE :q
                OR keyprogramming LIKE :q
             ORDER BY name'
        );
        $stmt->execute([':q' => '%' . $query . '%']);
    } else {
        $stmt = $pdo->query('SELECT id, name, email, keyprogramming FROM cvs ORDER BY name');
    }

    $cvs = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Database error. Please try again later.';
}
?>

<div class="page-heading">
    <h1>Search CVs</h1>
    <p>Search by name or programming language.</p>
</div>

<form method="get" action="/search.php" class="search-bar">
    <input type="text" name="q" value="<?= escape($query) ?>" placeholder="e.g. Python, React, Java…" autofocus>
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($query !== ''): ?>
        <a href="/search.php" class="btn btn-outline">Clear</a>
    <?php endif; ?>
</form>

<?php if ($error): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php else: ?>
    <?php if ($query !== ''): ?>
        <p style="margin-bottom:1.1rem;color:#666;">
            <?= count($cvs) ?> result<?= count($cvs) !== 1 ? 's' : '' ?> for
            &ldquo;<?= escape($query) ?>&rdquo;
        </p>
    <?php endif; ?>

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
                <td colspan="3">
                    <?= $query !== '' ? 'No results found for &ldquo;' . escape($query) . '&rdquo;.' : 'No CVs found.' ?>
                </td>
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
