<?php 
$title = 'Register for Event';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Register for <?= e($event['event_name']) ?></h1>
    </div>

    <div class="card">
        <p><?= e($event['date']) ?> · <?= e($event['location']) ?></p>

        <?php if ($msg): ?>
            <div class="alert alert-<?= e($type) ?>"><?= e($msg) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
            <button class="btn btn-primary">Confirm Registration</button>
            <a class="btn btn-secondary" href="<?= url('event/' . $event['event_id']) ?>">Back</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>