<?php
require_once '../config/database.php';

// Get available events
$sql    = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse all available events and register for attendance on EventFlow.">
    <title>Browse Events — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'browse'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">

        <div class="page-header">
            <h1>Available Events</h1>
            <p>Discover upcoming events and secure your spot today.</p>
        </div>

        <!-- Client-side filter -->
        <div class="search-bar" id="event-filter-bar">
            <input type="text" id="event-filter" placeholder="🔍  Filter events by name, status or location…">
            <button type="button" class="btn btn-secondary" id="clear-filter-btn" style="display:none;">✕ Clear</button>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>

        <div class="event-grid" id="event-grid">
            <?php while ($row = $result->fetch_assoc()):
                $s              = strtolower($row['status']);
                $badge          = "badge-$s";
                $formatted_date = date("M j, Y", strtotime($row['date']));
                $formatted_time = date("g:i A", strtotime($row['time']));
                $is_available   = ($s !== 'cancelled' && $s !== 'completed');
            ?>

            <div class="event-card"
                 data-name="<?php echo strtolower(htmlspecialchars($row['event_name'])); ?>"
                 data-status="<?php echo strtolower(htmlspecialchars($row['status'])); ?>"
                 data-location="<?php echo strtolower(htmlspecialchars($row['location'])); ?>">

                <div class="event-card-header">
                    <h2><?php echo htmlspecialchars($row['event_name']); ?></h2>
                    <span class="badge <?php echo htmlspecialchars($badge); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </div>

                <?php if ($row['description']): ?>
                <p class="description"><?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '…')); ?></p>
                <?php endif; ?>

                <div class="meta-row">
                    <span class="meta-icon">📅</span>
                    <span><?php echo htmlspecialchars($formatted_date); ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-icon">🕐</span>
                    <span><?php echo htmlspecialchars($formatted_time); ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-icon">📍</span>
                    <span><?php echo htmlspecialchars($row['location']); ?></span>
                </div>

                <div class="card-footer">
                    <div class="capacity-info">
                        👥 <?php echo (int) $row['capacity']; ?> seats
                    </div>
                    <?php if ($is_available): ?>
                    <a href="register_event.php?id=<?php echo (int) $row['event_id']; ?>"
                       class="btn btn-primary btn-sm"
                       id="register-<?php echo (int) $row['event_id']; ?>">
                        🎟️ Register
                    </a>
                    <?php else: ?>
                    <span class="btn btn-secondary btn-sm" style="opacity:0.4;cursor:default;">
                        Unavailable
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php endwhile; ?>
        </div>

        <div id="no-events-msg" style="display:none;text-align:center;padding:3rem;color:var(--text-muted);">
            No events match your filter. <button type="button" class="btn btn-secondary btn-sm" id="reset-filter-btn" style="margin-left:0.5rem;">Reset</button>
        </div>

        <?php else: ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">🗓️</div>
                <h3>No events available</h3>
                <p>Check back soon — new events are added regularly!</p>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <script>
        // ── Client-side event filter ─────────────────────────────
        const filterInput  = document.getElementById('event-filter');
        const clearBtn     = document.getElementById('clear-filter-btn');
        const noEventsMsg  = document.getElementById('no-events-msg');
        const resetBtn     = document.getElementById('reset-filter-btn');

        function filterEvents() {
            if (!filterInput) return;
            const q     = filterInput.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.event-card');
            let visible = 0;

            cards.forEach(card => {
                const name     = card.dataset.name     || '';
                const status   = card.dataset.status   || '';
                const location = card.dataset.location || '';

                if (!q || name.includes(q) || status.includes(q) || location.includes(q)) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noEventsMsg) {
                noEventsMsg.style.display = visible === 0 && q ? 'block' : 'none';
            }
            if (clearBtn) {
                clearBtn.style.display = q ? 'inline-flex' : 'none';
            }
        }

        if (filterInput) {
            filterInput.addEventListener('input', filterEvents);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                filterInput.value = '';
                filterEvents();
                filterInput.focus();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                filterInput.value = '';
                filterEvents();
            });
        }

        // ── Animate cards on load ─────────────────────────────
        document.querySelectorAll('.event-card').forEach((card, i) => {
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(16px)';
            card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
            setTimeout(() => {
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0)';
            }, 60 + i * 50);
        });

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