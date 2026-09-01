<?php 
Auth::start(); 
$flash = flash_message(); 
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title ?? APP_NAME) ?> — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <nav class="topbar">
        <a class="brand" href="<?= url('') ?>">
            <span class="brand-icon">🎫</span> EventFlow
        </a>
        <button class="nav-toggle" id="nav-toggle" type="button" aria-label="Toggle navigation">☰</button>
        
        <div class="nav-links" id="nav-links">
            <a class="<?= active('') ?>" href="<?= url('') ?>">🏠 Home</a>
            
            <?php 
            $role = Auth::role();
            if (in_array($role, ['organizer', 'admin'])): 
            ?>
                <a class="<?= active('organizer/events') ?>" href="<?= url('organizer/events') ?>">📋 Manage Events</a>
                <a class="<?= active('organizer/create') ?>" href="<?= url('organizer/create') ?>">➕ Create Event</a>
                <a class="<?= active('organizer/participants') ?>" href="<?= url('organizer/participants') ?>">👥 Participants</a>
            <?php endif; ?>

            <?php if (in_array($role, ['participant', 'admin'])): ?>
                <a class="<?= active('events') ?>" href="<?= url('events') ?>">🎟️ Browse Events</a>
                <a class="<?= active('my-tickets') ?>" href="<?= url('my-tickets') ?>">🎫 My Tickets</a>
            <?php endif; ?>

            <?php if (in_array($role, ['staff', 'admin'])): ?>
                <a class="<?= active('ticket/verify') ?>" href="<?= url('ticket/verify') ?>">🔎 Verify</a>
                <a class="<?= active('ticket/checkin') ?>" href="<?= url('ticket/checkin') ?>">✅ Check-in</a>
            <?php endif; ?>

            <?php if (Auth::check()): ?>
                <a class="<?= active('announcements') ?>" href="<?= url('announcements') ?>">📢 Announcements</a>
                <?php if ($role === 'admin'): ?>
                    <a class="<?= active('admin') ?>" href="<?= url('admin') ?>">🛡️ Admin</a>
                <?php endif; ?>
                <a class="<?= active('profile') ?>" href="<?= url('profile') ?>">👤 Profile</a>
                <a href="<?= url('logout') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= url('login') ?>">Login</a>
                <a href="<?= url('register') ?>">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if ($flash): ?>
        <div class="page-wrapper">
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        </div>
    <?php endif; ?>