<?php 
$title = 'Check-in';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Event Staff Check-in</h1>
    </div>

    <div class="card form-container">
        <form method="post">
            <div class="form-group">
                <label>Ticket Code</label>
                <input name="ticket_code" value="<?= e($code) ?>" required>
            </div>
            <button class="btn btn-primary">Check-in</button>
        </form>

        <?php if ($message): ?>
            <div class="alert alert-<?= e($type) ?>"><?= e($message) ?></div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>