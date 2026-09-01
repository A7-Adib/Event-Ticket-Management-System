<?php 
$title = 'Register';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Create Participant Account</h1>
    </div>

    <div class="card form-container auth-card">
        <?php if ($message): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" maxlength="100" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input name="phone" maxlength="20">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" minlength="6" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" minlength="6" required>
            </div>

            <button class="btn btn-primary">Register</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>