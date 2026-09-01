<?php 
$edit = isset($event);
$title = $edit ? 'Edit Event' : 'Create Event';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1><?= e($title) ?></h1>
    </div>

    <div class="card form-container">
        <?php if ($message): ?>
            <div class="alert alert-error"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="event_id" value="<?= e($event['event_id'] ?? 0) ?>">

            <div class="form-group">
                <label>Event Name</label>
                <input name="event_name" required value="<?= e($event['event_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= e($event['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" required value="<?= e($event['date'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Time</label>
                <input type="time" name="time" required value="<?= e($event['time'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Location</label>
                <input name="location" required value="<?= e($event['location'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" min="1" required value="<?= e($event['capacity'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <?php foreach (['Upcoming', 'Ongoing', 'Completed', 'Cancelled'] as $s): ?>
                        <option <?= $s === ($event['status'] ?? 'Upcoming') ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="0">None</option>
                    <?php while ($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['category_id'] ?>" <?= ((int)($event['category_id'] ?? 0) === (int)$c['category_id']) ? 'selected' : '' ?>><?= e($c['category_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button class="btn btn-primary">Save Event</button>
            <a class="btn btn-secondary" href="<?= url('organizer/events') ?>">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>