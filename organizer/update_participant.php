<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: participants.php");
    exit();
}

$registration_id = (int) $_GET['id'];
$message         = "";
$msg_type        = "success";

// Get participant registration information
$sql = "SELECT 
            registrations.registration_id,
            registrations.user_id,
            users.name,
            events.event_name,
            registrations.status
        FROM registrations
        JOIN users  ON registrations.user_id = users.user_id
        JOIN events ON registrations.event_id = events.event_id
        WHERE registrations.registration_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Registration not found.");
}

$participant = $result->fetch_assoc();

// Update status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];

    $update_sql  = "UPDATE registrations SET status = ? WHERE registration_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $registration_id);

    if ($update_stmt->execute()) {
        header("Location: participants.php");
        exit();
    } else {
        $message  = "❌ Error updating participant status.";
        $msg_type = "error";
    }

    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Update participant registration status on EventFlow.">
    <title>Update Participant — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'participants'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">

        <div class="page-header">
            <div class="header-row">
                <h1>Update Participant</h1>
                <a href="participants.php" class="btn btn-secondary" id="back-btn">← Back to Participants</a>
            </div>
            <p>Change the registration status for this participant.</p>
        </div>

        <?php if ($message != ""): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" id="form-alert">
            <span class="alert-icon">❌</span>
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Participant Info Card -->
        <div class="card" style="max-width:560px;margin-bottom:1.25rem;" id="participant-card">
            <div class="info-block-title">Registration Details</div>
            <div class="info-block">
                <div class="info-row">
                    <span class="lbl">Participant</span>
                    <span class="val"><strong><?php echo htmlspecialchars($participant['name']); ?></strong></span>
                </div>
                <div class="info-row">
                    <span class="lbl">User ID</span>
                    <span class="val">#<?php echo (int) $participant['user_id']; ?></span>
                </div>
                <div class="info-row">
                    <span class="lbl">Event</span>
                    <span class="val"><?php echo htmlspecialchars($participant['event_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="lbl">Current Status</span>
                    <span class="val">
                        <?php
                        $s     = strtolower($participant['status']);
                        $badge = "badge-$s";
                        ?>
                        <span class="badge <?php echo htmlspecialchars($badge); ?>" id="current-badge">
                            <?php echo htmlspecialchars($participant['status']); ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Update Form -->
        <div class="card form-container">
            <form method="POST" id="update-participant-form">
                <div class="form-group">
                    <label for="status">New Status</label>
                    <select id="status" name="status" required>
                        <?php foreach (['Registered', 'Confirmed', 'Attended', 'Cancelled'] as $opt): ?>
                        <option value="<?php echo $opt; ?>"
                            <?php if ($participant['status'] === $opt) echo 'selected'; ?>>
                            <?php echo $opt; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="update-btn">
                        🔄 Update Status
                    </button>
                    <a href="participants.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

    </div>

    <script>
        // ── Live badge preview when select changes ───────────────
        const statusSelect  = document.getElementById('status');
        const currentBadge  = document.getElementById('current-badge');
        const updateBtn     = document.getElementById('update-btn');

        const badgeClasses = {
            'registered': 'badge-registered',
            'confirmed':  'badge-confirmed',
            'attended':   'badge-attended',
            'cancelled':  'badge-cancelled'
        };

        statusSelect.addEventListener('change', function() {
            const val    = this.value;
            const key    = val.toLowerCase();
            const cls    = badgeClasses[key] || 'badge-registered';

            // Remove all badge classes and set the new one
            currentBadge.className = 'badge ' + cls;
            currentBadge.textContent = val;
        });

        // ── Submit feedback ─────────────────────────────────────
        document.getElementById('update-participant-form').addEventListener('submit', function() {
            updateBtn.disabled = true;
            updateBtn.textContent = '⏳ Updating…';
        });

        // Auto-hide alert
        const alert = document.getElementById('form-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.4s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 400);
            }, 5000);
        }

        // ── Hamburger toggle ─────────────────────────────────────
        const toggle   = document.getElementById('nav-toggle');
        const navLinks = document.getElementById('nav-links');
        toggle.addEventListener('click', function() {
            this.classList.toggle('open');
            navLinks.classList.toggle('open');
        });
    </script>

    <?php require '../includes/footer.php'; ?>

</body>
</html>