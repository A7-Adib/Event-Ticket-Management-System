<?php 
$title = 'Manage Events';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Manage Events</h1>
        <a class="btn btn-primary" href="<?= url('organizer/create') ?>">Create Event</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($e = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($e['event_name']) ?></td>
                        <td><?= e($e['date']) ?></td>
                        <td><?= e($e['location']) ?></td>
                        <td><?= e($e['capacity']) ?></td>
                        <td><?= e($e['registered_count']) ?></td>
                        <td>
                            <a href="<?= url('organizer/edit/' . $e['event_id']) ?>">Edit</a>
                            <a class="link-delete" onclick="return confirm('Delete this event and related registrations/tickets?')" href="<?= url('organizer/delete/' . $e['event_id']) ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>