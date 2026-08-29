<?php
/**
 * Shared Navbar Include
 * Usage: set $base ('' for root, '../' for subdirs) and $active before including.
 * Example: $base = '../'; $active = 'all-events'; require $base . 'includes/nav.php';
 */
$base   = $base   ?? '';
$active = $active ?? '';
?>
<nav class="topbar">
    <a href="<?php echo $base; ?>index.php" class="brand">
        <span class="brand-icon">🎫</span>
        EventFlow
    </a>

    <!-- Mobile hamburger -->
    <button class="nav-toggle" id="nav-toggle" aria-label="Toggle navigation">
        <span></span><span></span><span></span>
    </button>

    <div class="nav-links" id="nav-links">
        <a href="<?php echo $base; ?>index.php"
           class="<?php echo $active === 'home' ? 'active' : ''; ?>">
           🏠 Home
        </a>
        <a href="<?php echo $base; ?>organizer/view_events.php"
           class="<?php echo $active === 'all-events' ? 'active' : ''; ?>">
           📋 All Events
        </a>
        <a href="<?php echo $base; ?>organizer/create_event.php"
           class="<?php echo $active === 'create-event' ? 'active' : ''; ?>">
           ➕ Create Event
        </a>
        <a href="<?php echo $base; ?>organizer/participants.php"
           class="<?php echo $active === 'participants' ? 'active' : ''; ?>">
           👥 Participants
        </a>
        <a href="<?php echo $base; ?>participant/events.php"
           class="nav-cta <?php echo $active === 'browse' ? 'active' : ''; ?>">
           🎟️ Browse Events
        </a>
    </div>
</nav>
