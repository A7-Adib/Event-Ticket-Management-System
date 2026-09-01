<?php 
$title = 'My Tickets';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>My Tickets</h1>
        <p>Your generated event tickets.</p>
    </div>

    <?php if ($tickets->num_rows): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Event</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($t = $tickets->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= e($t['ticket_code']) ?></strong></td>
                            <td><?= e($t['event_name']) ?></td>
                            <td><?= e($t['ticket_type']) ?></td>
                            <td><?= e($t['status']) ?></td>
                            <td><?= e($t['date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card">
            <p>No tickets have been generated yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>