<?php
require_once '../config/database.php';

$message  = "";
$msg_type = "success";

// Check if event ID exists in URL
if (!isset($_GET['id'])) {
    header("Location: events.php");
    exit();
}

$event_id = (int) $_GET['id'];

// Get selected event information
$sql  = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();

// When participant submits registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = (int) $_POST["user_id"];

    // First: check the user_id actually exists in the users table
    $user_chk = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $user_chk->bind_param("i", $user_id);
    $user_chk->execute();
    $user_chk_result = $user_chk->get_result();

    if ($user_chk_result->num_rows === 0) {
        $message  = "❌ User ID <strong>#" . $user_id . "</strong> does not exist. Please enter a valid User ID registered in the system.";
        $msg_type = "error";
    } else {
        // Check whether this user already registered for this event
        $check_sql  = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $event_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message  = "⚠️ You are already registered for this event.";
            $msg_type = "warning";
        } else {
            // Insert new registration
            $registration_date = date("Y-m-d H:i:s");
            $status            = "Registered";

            $insert_sql  = "INSERT INTO registrations (user_id, event_id, registration_date, status) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iiss", $user_id, $event_id, $registration_date, $status);

            try {
                if ($insert_stmt->execute()) {
                    $message  = "🎉 You have successfully registered for this event!";
                    $msg_type = "success";
                } else {
                    $message  = "❌ Registration failed. Please try again.";
                    $msg_type = "error";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1452) {
                    $message  = "❌ User ID <strong>#" . $user_id . "</strong> does not exist in the system. Please use a valid User ID.";
                } else {
                    $message  = "❌ Database error: " . htmlspecialchars($e->getMessage());
                }
                $msg_type = "error";
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
    }

    $user_chk->close();
}

// Format for display
$formatted_date = date("l, F j, Y", strtotime($event['date']));
$formatted_time = date("g:i A", strtotime($event['time']));
$status_class   = "badge-" . strtolower($event['status']);
$is_available   = !in_array(strtolower($event['status']), ['cancelled', 'completed']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for <?php echo htmlspecialchars($event['event_name']); ?> on EventFlow.">
    <title>Register — <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .register-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .field-error {
            display: block;
            font-size: 0.75rem;
            color: #fca5a5;
            margin-top: 0.3rem;
            min-height: 1rem;
        }

        @media (max-width: 640px) {
            .register-layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <?php $base = '../'; $active = 'browse'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">

        <div class="page-header">
            <div class="header-row">
                <h1>Register for Event</h1>
                <a href="events.php" class="btn btn-secondary" id="back-btn">← Back to Events</a>
            </div>
        </div>

        <?php if ($message != ""): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" id="reg-alert">
            <span class="alert-icon">
                <?php
                if ($msg_type === 'success') echo '🎉';
                elseif ($msg_type === 'warning') echo '⚠️';
                else echo '❌';
                ?>
            </span>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="register-layout">

            <!-- Event Details Card -->
            <div class="card" id="event-details-card">
                <div class="event-card-header" style="margin-bottom:1.1rem;">
                    <h2 style="font-size:1.2rem;"><?php echo htmlspecialchars($event['event_name']); ?></h2>
                    <span class="badge <?php echo htmlspecialchars($status_class); ?>">
                        <?php echo htmlspecialchars($event['status']); ?>
                    </span>
                </div>

                <?php if ($event['description']): ?>
                <p style="color:var(--text-muted);font-size:0.88rem;line-height:1.6;margin-bottom:1.1rem;">
                    <?php echo htmlspecialchars($event['description']); ?>
                </p>
                <?php endif; ?>

                <div class="info-block">
                    <div class="info-row">
                        <span class="lbl">Date</span>
                        <span class="val">📅 <?php echo htmlspecialchars($formatted_date); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Time</span>
                        <span class="val">🕐 <?php echo htmlspecialchars($formatted_time); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Location</span>
                        <span class="val">📍 <?php echo htmlspecialchars($event['location']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Capacity</span>
                        <span class="val">👥 <?php echo (int) $event['capacity']; ?> seats</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form Card -->
            <div class="card" id="registration-form-card">
                <h2 style="margin-bottom:0.35rem;">Complete Registration</h2>
                <p style="margin-bottom:1.25rem;font-size:0.85rem;">
                    Enter your user ID to register for this event.
                </p>

                <?php if (!$is_available): ?>
                <div class="alert alert-warning">
                    <span class="alert-icon">⚠️</span>
                    This event is no longer accepting registrations.
                </div>
                <?php else: ?>
                <form method="POST" id="register-form" novalidate>
                    <div class="form-group">
                        <label for="user_id">Your User ID</label>
                        <input type="number" id="user_id" name="user_id"
                               placeholder="Enter your user ID" min="1" required>
                        <span class="field-error" id="err-userid"></span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="register-btn">
                            🎟️ Register Now
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script>
        // ── Form validation ──────────────────────────────────────
        const form        = document.getElementById('register-form');
        const registerBtn = document.getElementById('register-btn');

        if (form) {
            form.addEventListener('submit', function(e) {
                const userIdInput = document.getElementById('user_id');
                const errEl       = document.getElementById('err-userid');
                const val         = parseInt(userIdInput.value);

                errEl.textContent = '';

                if (!val || val < 1) {
                    e.preventDefault();
                    errEl.textContent = 'Please enter a valid User ID (must be a positive number).';
                    userIdInput.focus();
                    return;
                }

                // Disable button to prevent double submit
                registerBtn.disabled    = true;
                registerBtn.textContent = '⏳ Registering…';
            });
        }

        // ── Auto-dismiss alert ─────────────────────────────────────
        const regAlert = document.getElementById('reg-alert');
        if (regAlert) {
            setTimeout(() => {
                regAlert.style.transition = 'opacity 0.4s ease';
                regAlert.style.opacity    = '0';
                setTimeout(() => regAlert.remove(), 400);
            }, 6000);
        }

        // ── Input focus highlight ────────────────────────────────
        const userIdField = document.getElementById('user_id');
        if (userIdField) {
            userIdField.addEventListener('focus', function() {
                this.select();
            });
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