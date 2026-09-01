<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($event['event_name']); ?> — EventFlow</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php
$base = '';
$active = 'all-events';
require 'includes/nav.php';
?>

<div class="page-wrapper">

    <div class="page-header">
        <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
        <p>Event details</p>
    </div>

    <div class="card">

        <?php if (!empty($event['description'])): ?>
            <h2>Description</h2>
            <p>
                <?php echo nl2br(htmlspecialchars($event['description'])); ?>
            </p>
            <br>
        <?php endif; ?>

        <h2>Event Information</h2>

        <div class="meta-row">
            <strong>Date:</strong>
            <span><?php echo htmlspecialchars($event['date']); ?></span>
        </div>

        <div class="meta-row">
            <strong>Time:</strong>
            <span><?php echo htmlspecialchars($event['time']); ?></span>
        </div>

        <div class="meta-row">
            <strong>Location:</strong>
            <span><?php echo htmlspecialchars($event['location']); ?></span>
        </div>

        <div class="meta-row">
            <strong>Capacity:</strong>
            <span><?php echo (int) $event['capacity']; ?> seats</span>
        </div>

        <div class="meta-row">
            <strong>Status:</strong>
            <span class="badge badge-<?php echo htmlspecialchars(strtolower($event['status'])); ?>">
                <?php echo htmlspecialchars($event['status']); ?>
            </span>
        </div>

        <div style="margin-top: 1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">

            <a href="participant/events.php" class="btn btn-primary">
                Browse & Register
            </a>

            <a href="index.php" class="btn btn-secondary">
                Back to Home
            </a>

        </div>

    </div>

</div>

<?php require 'includes/footer.php'; ?>

</body>
</html>