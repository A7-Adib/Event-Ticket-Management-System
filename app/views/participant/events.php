<?php 
$title = 'Browse Events';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Browse Events</h1>
        <p>Choose an event and register.</p>
    </div>

    <?php if ($events->num_rows): ?>
        <div class="event-grid">
            <?php while ($e = $events->fetch_assoc()): ?>
                <article class="event-card">
                    <img class="event-media" src="<?= e(event_image((int)$e['event_id'])) ?>" alt="<?= e($e['event_name']) ?>">
                    <div class="event-card-body">
                        <div class="event-card-header">
                            <h2><?= e($e['event_name']) ?></h2>
                            <span class="badge badge-<?= strtolower(e($e['status'])) ?>"><?= e($e['status']) ?></span>
                        </div>
                        <p class="description"><?= e(mb_strimwidth($e['description'] ?? '', 0, 120, '...')) ?></p>
                        <div class="meta-row">📅 <?= e($e['date']) ?> · <?= e($e['time']) ?></div>
                        <div class="meta-row">📍 <?= e($e['location']) ?></div>
                        <div class="card-footer">
                            <span><?= e(max(0, (int)$e['capacity'] - (int)$e['registered_count'])) ?> seats left</span>
                            <a class="btn btn-primary" href="<?= url('event/' . $e['event_id']) ?>">Details</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card empty-state">No events found.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>