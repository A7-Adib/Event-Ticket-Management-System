<?php 
$title = 'Login';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Welcome Back</h1>
        <p>Sign in to EventFlow.</p>
    </div>

    <div class="card form-container auth-card">
        <?php if ($message): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary">Login</button>
                <a class="btn btn-secondary" href="<?= url('register') ?>">Register</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>