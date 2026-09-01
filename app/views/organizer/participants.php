<?php 
$title = 'Participants';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Participants</h1>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Event</th>
                    <th>Registration</th>
                    <th>Ticket</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= e($r['attendee_name']) ?><br>
                            <small><?= e($r['email']) ?></small>
                        </td>
                        <td><?= e($r['event_name']) ?></td>
                        <td><?= e($r['status']) ?></td>
                        <td><?= e($r['ticket_code'] ?? 'Not generated') ?></td>
                        <td>
                            <form method="post" action="<?= url('organizer/participants/update') ?>" class="inline-form">
                                <input type="hidden" name="registration_id" value="<?= $r['registration_id'] ?>">
                                <select name="status">
                                    <?php foreach (['Registered', 'Confirmed', 'Attended', 'Cancelled'] as $st): ?>
                                        <option <?= $r['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-sm">Update</button>
                            </form>
                            <?php if (empty($r['ticket_code']) && $r['status'] !== 'Cancelled'): ?>
                                <a class="btn btn-primary btn-sm" href="<?= url('ticket/generate/' . $r['registration_id']) ?>">Generate Ticket</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>