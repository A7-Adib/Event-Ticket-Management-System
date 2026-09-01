<?php 
$title = 'Edit User';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Edit User</h1>
    </div>
    
    <div class="card form-container">
        <?php if ($message): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
            
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
            
            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <?php foreach (['Admin', 'Organizer', 'Participant', 'Staff'] as $r): ?>
                        <option <?= $user['role'] === $r ? 'selected' : '' ?>><?= $r ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button class="btn btn-primary">Save</button>
            <a class="btn btn-secondary" href="<?= url('admin/users') ?>">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>