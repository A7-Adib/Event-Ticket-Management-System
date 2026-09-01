<?php 
$title = 'Announcements';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Announcements</h1>
    </div>

    <?php if (in_array($role, ['admin', 'organizer'], true)): ?>
        <div class="card form-container">
            <h2>Post Announcement</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-error"><?= e($message) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" required></textarea>
                </div>
                
                <button class="btn btn-primary">Publish</button>
            </form>
        </div>
    <?php endif; ?>

    <?php while ($a = $result->fetch_assoc()): ?>
        <div class="card announcement-card">
            <h2><?= e($a['title']) ?></h2>
            <p><?= nl2br(e($a['message'])) ?></p>
            <small><?= e($a['created_at']) ?> · <?= e($a['author'] ?? 'System') ?></small>
        </div>
    <?php endwhile; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>