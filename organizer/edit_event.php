<?php
require_once '../config/database.php';

// Check if event ID exists
if (!isset($_GET['id'])) {
    header("Location: view_events.php");
    exit();
}

$event_id = (int) $_GET['id'];

// Get the selected event from database
$sql  = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();

$message  = "";
$msg_type = "success";

// Update event when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $event_name   = trim($_POST["event_name"]);
    $description  = trim($_POST["description"]);
    $date         = $_POST["date"];
    $time         = $_POST["time"];
    $location     = trim($_POST["location"]);
    $capacity     = (int) $_POST["capacity"];
    $status       = $_POST["status"];
    $organizer_id = (int) $_POST["organizer_id"];

    // Basic server-side check: organizer must exist in users table
    $chk = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $chk->bind_param("i", $organizer_id);
    $chk->execute();
    $chk_result = $chk->get_result();

    if ($chk_result->num_rows === 0) {
        $message  = "❌ Organizer ID <strong>#" . $organizer_id . "</strong> does not exist. Please enter a valid User ID from the users table.";
        $msg_type = "error";
    } else {
        $update_sql = "UPDATE events SET
            event_name    = ?,
            description   = ?,
            date          = ?,
            time          = ?,
            location      = ?,
            capacity      = ?,
            status        = ?,
            organizer_id  = ?
            WHERE event_id = ?";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "sssssisii",
            $event_name,
            $description,
            $date,
            $time,
            $location,
            $capacity,
            $status,
            $organizer_id,
            $event_id
        );

        try {
            if ($update_stmt->execute()) {
                header("Location: view_events.php");
                exit();
            } else {
                $message  = "❌ Error updating event. Please try again.";
                $msg_type = "error";
            }
        } catch (mysqli_sql_exception $e) {
            // Foreign key constraint failed (errno 1452)
            if ($e->getCode() === 1452) {
                $message  = "❌ Organizer ID <strong>#" . $organizer_id . "</strong> does not exist in the system. Please use a valid User ID.";
            } else {
                $message  = "❌ Database error: " . htmlspecialchars($e->getMessage());
            }
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit event details on EventFlow.">
    <title>Edit Event — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'all-events'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">
        <div class="page-header">
            <div class="header-row">
                <h1>Edit Event</h1>
                <a href="view_events.php" class="btn btn-secondary" id="back-btn">← Back to Events</a>
            </div>
            <p>Editing: <strong style="color:var(--text)"><?php echo htmlspecialchars($event['event_name']); ?></strong></p>
        </div>

        <?php if ($message != ""): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" id="form-alert">
            <span class="alert-icon">❌</span>
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="card form-container">
            <form method="POST" id="edit-event-form" novalidate>

                <div class="form-group">
                    <label for="event_name">Event Name</label>
                    <input type="text" id="event_name" name="event_name"
                           value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
                    <span class="field-error" id="err-name"></span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="date">Event Date</label>
                        <input type="date" id="date" name="date"
                               value="<?php echo htmlspecialchars($event['date']); ?>" required>
                        <span class="field-error" id="err-date"></span>
                    </div>
                    <div class="form-group">
                        <label for="time">Event Time</label>
                        <input type="time" id="time" name="time"
                               value="<?php echo htmlspecialchars($event['time']); ?>" required>
                        <span class="field-error" id="err-time"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location"
                           value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    <span class="field-error" id="err-location"></span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" id="capacity" name="capacity"
                               value="<?php echo (int) $event['capacity']; ?>" min="1" required>
                        <span class="field-error" id="err-capacity"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <?php foreach (['Upcoming', 'Ongoing', 'Completed', 'Cancelled'] as $opt): ?>
                            <option value="<?php echo $opt; ?>"
                                <?php if ($event['status'] === $opt) echo 'selected'; ?>>
                                <?php echo $opt; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="organizer_id">Organizer ID</label>
                    <input type="number" id="organizer_id" name="organizer_id"
                           value="<?php echo (int) $event['organizer_id']; ?>" min="1" required>
                    <span class="field-error" id="err-organizer"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="save-btn">
                        💾 Save Changes
                    </button>
                    <a href="view_events.php" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <style>
        .field-error {
            display: block;
            font-size: 0.75rem;
            color: #fca5a5;
            margin-top: 0.3rem;
            min-height: 1rem;
        }
    </style>

    <script>
        // ── Form validation ──────────────────────────────────────
        const form    = document.getElementById('edit-event-form');
        const saveBtn = document.getElementById('save-btn');

        function showError(id, msg) {
            document.getElementById(id).textContent = msg;
        }

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        }

        function validateForm() {
            clearErrors();
            let valid = true;

            if (!document.getElementById('event_name').value.trim()) {
                showError('err-name', 'Event name is required.'); valid = false;
            }
            if (!document.getElementById('date').value) {
                showError('err-date', 'Please select a date.'); valid = false;
            }
            if (!document.getElementById('time').value) {
                showError('err-time', 'Please select a time.'); valid = false;
            }
            if (!document.getElementById('location').value.trim()) {
                showError('err-location', 'Location is required.'); valid = false;
            }
            const cap = parseInt(document.getElementById('capacity').value);
            if (!cap || cap < 1) {
                showError('err-capacity', 'Enter a valid capacity.'); valid = false;
            }
            const org = parseInt(document.getElementById('organizer_id').value);
            if (!org || org < 1) {
                showError('err-organizer', 'Enter a valid organizer ID.'); valid = false;
            }

            return valid;
        }

        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return;
            }
            saveBtn.disabled = true;
            saveBtn.textContent = '⏳ Saving…';
        });

        // ── Track unsaved changes ────────────────────────────────
        let hasChanges = false;
        form.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('change', () => hasChanges = true);
        });

        window.addEventListener('beforeunload', function(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        form.addEventListener('submit', () => hasChanges = false);

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