<?php 
$title = 'Ticket Verification';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Ticket Verification</h1>
    </div>

    <div class="card form-container">
        <form method="post">
            <div class="form-group">
                <label>Ticket Code</label>
                <input name="ticket_code" value="<?= e($code) ?>" placeholder="TKTXXXXXXXX" required>
            </div>
            <button class="btn btn-primary">Verify</button>
        </form>

        <?php if ($message): ?>
            <div class="alert alert-<?= e($type) ?>"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($ticket): ?>
            <div class="ticket">
                <h3>Ticket Information</h3>
                <p><strong>Code:</strong> <?= e($ticket['ticket_code']) ?></p>
                <p><strong>Attendee:</strong> <?= e($ticket['attendee_name']) ?></p>
                <p><strong>Event:</strong> <?= e($ticket['event_name']) ?></p>
                <p><strong>Type:</strong> <?= e($ticket['ticket_type']) ?></p>
                <p><strong>Status:</strong> <?= e($ticket['status']) ?></p>
                
                <?php if ($ticket['status'] === 'Valid'): ?>
                    <a class="btn btn-primary" href="<?= url('ticket/checkin/' . $ticket['ticket_code']) ?>">Proceed to Check-in</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>