<?php
require_once '../config/database.php';

$message  = "";
$msg_type = "success";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $event_name   = trim($_POST["event_name"]);
    $description  = trim($_POST["description"]);
    $date         = $_POST["date"];
    $time         = $_POST["time"];
    $location     = trim($_POST["location"]);
    $capacity     = (int) $_POST["capacity"];
    $status       = $_POST["status"];
    $organizer_id = (int) $_POST["organizer_id"];

    // Check organizer_id exists in users table before inserting
    $chk = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $chk->bind_param("i", $organizer_id);
    $chk->execute();
    $chk_result = $chk->get_result();

    if ($chk_result->num_rows === 0) {
        $message  = "❌ Organizer ID <strong>#" . $organizer_id . "</strong> does not exist. Please enter a valid User ID from the users table.";
        $msg_type = "error";
    } else {
        $sql = "INSERT INTO events 
                (event_name, description, date, time, location, capacity, status, organizer_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssisi",
            $event_name,
            $description,
            $date,
            $time,
            $location,
            $capacity,
            $status,
            $organizer_id
        );

        try {
            if ($stmt->execute()) {
                $message  = "✅ Event <strong>" . htmlspecialchars($event_name) . "</strong> created successfully!";
                $msg_type = "success";
            } else {
                $message  = "❌ Error creating event. Please try again.";
                $msg_type = "error";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1452) {
                $message  = "❌ Organizer ID <strong>#" . $organizer_id . "</strong> does not exist. Please use a valid User ID.";
            } else {
                $message  = "❌ Database error: " . htmlspecialchars($e->getMessage());
            }
            $msg_type = "error";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create a new event on EventFlow.">
    <title>Create Event — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'create-event'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">
        <div class="page-header">
            <div class="header-row">
                <h1>Create New Event</h1>
                <a href="view_events.php" class="btn btn-secondary" id="back-btn">← Back to Events</a>
            </div>
            <p>Fill in the details below to publish a new event.</p>
        </div>

        <?php if ($message != ""): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" id="form-alert">
            <span class="alert-icon"><?php echo $msg_type === 'success' ? '✅' : '❌'; ?></span>
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="card form-container">
            <form action="create_event.php" method="POST" id="create-event-form" novalidate>

                <div class="form-group">
                    <label for="event_name">Event Name</label>
                    <input type="text" id="event_name" name="event_name"
                           placeholder="e.g. Tech Summit 2026" required>
                    <span class="field-error" id="err-name"></span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              placeholder="Describe the event, what attendees can expect…"></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="date">Event Date</label>
                        <input type="date" id="date" name="date" required>
                        <span class="field-error" id="err-date"></span>
                    </div>
                    <div class="form-group">
                        <label for="time">Event Time</label>
                        <input type="time" id="time" name="time" required>
                        <span class="field-error" id="err-time"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location"
                           placeholder="e.g. City Convention Center, Hall A" required>
                    <span class="field-error" id="err-location"></span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" id="capacity" name="capacity"
                               placeholder="e.g. 200" min="1" required>
                        <span class="field-error" id="err-capacity"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="" disabled selected>Select status…</option>
                            <option value="Upcoming">Upcoming</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <span class="field-error" id="err-status"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="organizer_id">Organizer ID</label>
                    <input type="number" id="organizer_id" name="organizer_id"
                           placeholder="e.g. 1" min="1" required>
                    <span class="field-error" id="err-organizer"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        ✨ Create Event
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
        // ── Basic Form Validation ─────────────────────────────────
        const form      = document.getElementById('create-event-form');
        const submitBtn = document.getElementById('submit-btn');

        // Set today as minimum date
        document.getElementById('date').min = new Date().toISOString().split('T')[0];

        function showError(id, msg) {
            document.getElementById(id).textContent = msg;
        }

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        }

        function validateForm() {
            clearErrors();
            let valid = true;

            const name = document.getElementById('event_name').value.trim();
            if (!name) { showError('err-name', 'Event name is required.'); valid = false; }

            const date = document.getElementById('date').value;
            if (!date) { showError('err-date', 'Please select a date.'); valid = false; }

            const time = document.getElementById('time').value;
            if (!time) { showError('err-time', 'Please select a time.'); valid = false; }

            const location = document.getElementById('location').value.trim();
            if (!location) { showError('err-location', 'Location is required.'); valid = false; }

            const capacity = parseInt(document.getElementById('capacity').value);
            if (!capacity || capacity < 1) { showError('err-capacity', 'Enter a valid capacity (min 1).'); valid = false; }

            const status = document.getElementById('status').value;
            if (!status) { showError('err-status', 'Please select a status.'); valid = false; }

            const orgId = parseInt(document.getElementById('organizer_id').value);
            if (!orgId || orgId < 1) { showError('err-organizer', 'Enter a valid organizer ID.'); valid = false; }

            return valid;
        }

        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return;
            }
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Creating…';
        });

        // Auto-hide alert after 5 seconds
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