<?php 
$title = 'Update Profile';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Update Profile</h1>
    </div>

    <div class="card form-container">
        <?php if ($message): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" value="<?= e($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" value="<?= e($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input name="phone" value="<?= e($user['phone']) ?>">
            </div>

            <button class="btn btn-primary">Update</button>
            <a class="btn btn-secondary" href="<?= url('profile') ?>">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>