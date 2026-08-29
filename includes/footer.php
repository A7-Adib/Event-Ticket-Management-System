<?php
/**
 * Shared Footer Include
 * Usage: set $base before including.
 * Example: $base = '../'; require $base . 'includes/footer.php';
 */
$base = $base ?? '';
?>
<footer class="site-footer">
    <div class="footer-inner">

        <div class="footer-brand">
            <a href="<?php echo $base; ?>index.php" class="brand footer-brand-link">
                <span class="brand-icon">🎫</span>
                EventFlow
            </a>
            <p class="footer-tagline">Event management &amp; ticketing system for everyone.</p>
        </div>

        <div class="footer-links-group">
            <h4>Organizer</h4>
            <ul>
                <li><a href="<?php echo $base; ?>organizer/view_events.php">📋 All Events</a></li>
                <li><a href="<?php echo $base; ?>organizer/create_event.php">➕ Create Event</a></li>
                <li><a href="<?php echo $base; ?>organizer/participants.php">👥 Participants</a></li>
            </ul>
        </div>

        <div class="footer-links-group">
            <h4>Participant</h4>
            <ul>
                <li><a href="<?php echo $base; ?>participant/events.php">🎟️ Browse Events</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> EventFlow — Event Ticket Management System. All rights reserved.</p>
    </div>
</footer>
