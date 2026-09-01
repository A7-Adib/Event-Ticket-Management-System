<?php 
$title = 'Profile';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>My Profile</h1>
    </div>

    <div class="card form-container">
        <p><strong>Name:</strong> <?= e($user['name']) ?></p>
        <p><strong>Email:</strong> <?= e($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= e($user['phone']) ?></p>
        <p><strong>Role:</strong> <?= e($user['role']) ?></p>

        <a class="btn btn-primary" href="<?= url('profile/edit') ?>">Update Profile</a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>