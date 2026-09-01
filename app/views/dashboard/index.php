<?php 
$title = 'Dashboard';
require __DIR__ . '/../layouts/header.php'; 
?>

<div class="page-wrapper">
    <section class="hero">
        <span class="hero-badge">🎟️ EventFlow</span>
        <h1><?= Auth::check() ? 'Welcome, ' . e($_SESSION['name']) . '!' : 'Plan. Register. Attend.' ?></h1>
        <p>Manage events, registrations, tickets and secure check-in from one connected event management system.</p>
        
        <div class="hero-actions">
            <?php if (!Auth::check()): ?>
                <a class="btn btn-primary btn-lg" href="<?= url('register') ?>">Create Account</a>
                <a class="btn btn-secondary btn-lg" href="<?= url('login') ?>">Login</a>
            <?php else: ?>
                <?php if ($role === 'participant'): ?>
                    <a class="btn btn-primary" href="<?= url('events') ?>">Browse Events</a>
                    <a class="btn btn-secondary" href="<?= url('my-tickets') ?>">My Tickets</a>
                <?php elseif ($role === 'organizer'): ?>
                    <a class="btn btn-primary" href="<?= url('organizer/create') ?>">Create Event</a>
                    <a class="btn btn-secondary" href="<?= url('organizer/participants') ?>">Participants</a>
                <?php elseif ($role === 'staff'): ?>
                    <a class="btn btn-primary" href="<?= url('ticket/verify') ?>">Verify Ticket</a>
                    <a class="btn btn-secondary" href="<?= url('ticket/checkin') ?>">Check-in</a>
                <?php elseif ($role === 'admin'): ?>
                    <a class="btn btn-primary" href="<?= url('admin') ?>">Admin Panel</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <div class="section-title">Upcoming Events</div>

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
                            <span>👥 <?= e($e['capacity'] - $e['registered_count']) ?> seats left</span>
                            <a class="btn btn-primary btn-sm" href="<?= url('event/' . $e['event_id']) ?>">View Details</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card empty-state">No upcoming events are available right now.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>