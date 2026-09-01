<?php 
$title = $event['event_name'];
require __DIR__ . '/../layouts/header.php';
$available = max(0, (int)$event['capacity'] - (int)$event['registered_count']);
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1><?= e($event['event_name']) ?></h1>
        <p><?= e($event['category_name'] ?? 'Event') ?></p>
    </div>

    <div class="card">
        <h2>Description</h2>
        <p><?= nl2br(e($event['description'] ?? 'No description.')) ?></p>
        
        <div class="meta-row">📅 <?= e($event['date']) ?></div>
        <div class="meta-row">⏰ <?= e($event['time']) ?></div>
        <div class="meta-row">📍 <?= e($event['location']) ?></div>
        <div class="meta-row">👤 Organizer: <?= e($event['organizer_name']) ?></div>
        <div class="meta-row">👥 <?= $available ?> of <?= e($event['capacity']) ?> seats available</div>

        <div class="form-actions">
            <a class="btn btn-secondary" href="<?= url('') ?>">Back</a>
            <?php if (Auth::role() === 'participant' || Auth::role() === 'admin'): ?>
                <a class="btn btn-primary" href="<?= url('register-event/' . $event['event_id']) ?>">Register</a>
            <?php elseif (!Auth::check()): ?>
                <a class="btn btn-primary" href="<?= url('login') ?>">Login to Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
