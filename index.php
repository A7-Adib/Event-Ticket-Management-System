<?php
require_once 'config/database.php';

// Get upcoming events
$sql = "SELECT * FROM events ORDER BY date ASC, event_id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EventFlow - Event and Ticket Management System">
    <title>EventFlow — Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php
$base = '';
$active = 'home';
require 'includes/nav.php';
?>

<div class="page-wrapper">

    <div class="page-header">
        <h1>Welcome to EventFlow</h1>
        <p>Discover upcoming events, create events, and manage participants easily.</p>
    </div>

    <div class="card" style="margin-bottom: 2rem;">
        <h2>Event & Ticket Management System</h2>
        <p>
            Browse available events and register for the events you want to attend.
            Organizers can also create, edit, and manage events and participants.
        </p>

        <div style="margin-top: 1.25rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="participant/events.php" class="btn btn-primary">
                Browse Events
            </a>

            <a href="organizer/create_event.php" class="btn btn-secondary">
                Create Event
            </a>
        </div>
    </div>

    <div class="page-header">
        <h2>Upcoming Events</h2>
        <p>Explore the events currently available in the system.</p>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>

        <div class="event-grid">

            <?php while ($event = $result->fetch_assoc()): ?>

                <div class="event-card">

                    <div class="event-card-header">
                        <h2>
                            <?php echo htmlspecialchars($event['event_name']); ?>
                        </h2>

                        <?php
                        $status = strtolower($event['status']);
                        ?>

                        <span class="badge badge-<?php echo htmlspecialchars($status); ?>">
                            <?php echo htmlspecialchars($event['status']); ?>
                        </span>
                    </div>

                    <?php if (!empty($event['description'])): ?>
                        <p class="description">
                            <?php
                            echo htmlspecialchars(
                                mb_strimwidth($event['description'], 0, 100, '...')
                            );
                            ?>
                        </p>
                    <?php endif; ?>

                    <div class="meta-row">
                        <span class="meta-icon">📅</span>
                        <span>
                            <?php echo htmlspecialchars($event['date']); ?>
                        </span>
                    </div>

                    <div class="meta-row">
                        <span class="meta-icon">📍</span>
                        <span>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </span>
                    </div>

                    <div class="card-footer">

                        <div class="capacity-info">
                            👥 <?php echo (int)$event['capacity']; ?> seats
                        </div>

                        <a
                            href="event-details.php?id=<?php echo (int)$event['event_id']; ?>"
                            class="btn btn-primary btn-sm"
                        >
                            View Details
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="card">
            <div class="empty-state">
                <h3>No events available</h3>
                <p>New events will appear here when they are created.</p>

                <a href="organizer/create_event.php" class="btn btn-primary">
                    Create First Event
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require 'includes/footer.php'; ?>

</body>
</html>