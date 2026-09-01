<?php 
$title = 'Generate Ticket';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Generate Ticket</h1>
        <p>Generate a ticket from a confirmed participant registration.</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= e($type) ?>"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if (!$reg): ?>
        <div class="card">
            <h2>Select Registration</h2>
            <?php if ($registrations && $registrations->num_rows): ?>
                <div class="table-wrapper">
                    <table>
                        <tr>
                            <th>Participant</th>
                            <th>Event</th>
                            <th>Action</th>
                        </tr>
                        <?php while ($r = $registrations->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($r['attendee_name']) ?></td>
                                <td><?= e($r['event_name']) ?></td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?= url('ticket/generate/' . $r['registration_id']) ?>">Select</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>No eligible registrations without tickets.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="card form-container">
            <h2><?= e($reg['event_name']) ?></h2>
            <p>Participant: <strong><?= e($reg['attendee_name']) ?></strong></p>

            <form method="post">
                <input type="hidden" name="registration_id" value="<?= $reg['registration_id'] ?>">
                
                <div class="form-group">
                    <label>Ticket Type</label>
                    <select name="ticket_type">
                        <option>Regular</option>
                        <option>VIP</option>
                        <option>Student</option>
                    </select>
                </div>

                <button class="btn btn-primary">Generate Ticket</button>
                <a class="btn btn-secondary" href="<?= url('organizer/participants') ?>">Back</a>
            </form>

            <?php if ($code): ?>
                <p><strong>Ticket Code:</strong> <?= e($code) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>