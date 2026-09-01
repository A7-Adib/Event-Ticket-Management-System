<?php 
$title = 'Admin Panel';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Admin Panel</h1>
    </div>

    <div class="stats-grid">
        <?php foreach (['users' => 'Users', 'events' => 'Events', 'registrations' => 'Registrations', 'tickets' => 'Tickets', 'check_in' => 'Check-ins'] as $k => $label): ?>
            <div class="stat-card">
                <div class="stat-label"><?= $label ?></div>
                <div class="stat-value"><?= $counts[$k] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <a class="btn btn-primary" href="<?= url('admin/users') ?>">Manage Users</a>
        <a class="btn btn-secondary" href="<?= url('organizer/events') ?>">Manage Events</a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>