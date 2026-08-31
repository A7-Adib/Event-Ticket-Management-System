<?php
require_once '../config/database.php';

$sql    = "SELECT * FROM events ORDER BY date ASC, event_id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View and manage all events on EventFlow.">
    <title>All Events — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'all-events'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">

        <div class="page-header">
            <div class="header-row">
                <h1>All Events</h1>
                <a href="create_event.php" class="btn btn-primary" id="create-btn">
                    ➕ Create Event
                </a>
            </div>
            <p>Manage and monitor all events in the system.</p>
        </div>

        <?php
        // Count stats
        $total = $upcoming = $ongoing = $completed = 0;
        $all_events = [];
        if ($result && $result->num_rows > 0) {
            while ($r = $result->fetch_assoc()) {
                $all_events[] = $r;
                $total++;
                $s = strtolower($r['status']);
                if ($s === 'upcoming')  $upcoming++;
                if ($s === 'ongoing')   $ongoing++;
                if ($s === 'completed') $completed++;
            }
        }
        ?>

        <!-- Stats -->
        <div class="stats-grid" id="stats-grid">
            <div class="stat-card accent-purple">
                <div class="stat-label">Total Events</div>
                <div class="stat-value purple"><?php echo $total; ?></div>
                <div class="stat-desc">All time</div>
            </div>
            <div class="stat-card accent-cyan">
                <div class="stat-label">Upcoming</div>
                <div class="stat-value cyan"><?php echo $upcoming; ?></div>
                <div class="stat-desc">Scheduled ahead</div>
            </div>
            <div class="stat-card accent-orange">
                <div class="stat-label">Ongoing</div>
                <div class="stat-value orange"><?php echo $ongoing; ?></div>
                <div class="stat-desc">Happening now</div>
            </div>
            <div class="stat-card accent-green">
                <div class="stat-label">Completed</div>
                <div class="stat-value green"><?php echo $completed; ?></div>
                <div class="stat-desc">Successfully done</div>
            </div>
        </div>

        <!-- Search (client-side) -->
        <?php if (count($all_events) > 0): ?>
        <div class="search-bar" id="table-search-bar">
            <input type="text" id="table-search" placeholder="🔍  Filter events by name, status or location…">
        </div>
        <?php endif; ?>

        <!-- Events Table -->
        <?php if (count($all_events) > 0): ?>
        <div class="table-wrapper">
            <table id="events-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date &amp; Time</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="events-tbody">
                    <?php foreach ($all_events as $row): ?>
                    <tr>
                        <td class="muted"><?php echo (int) $row['event_id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['event_name']); ?></strong>
                            <?php if ($row['description']): ?>
                            <br><span style="font-size:0.78rem;color:var(--text-muted);">
                                <?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 55, '…')); ?>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="muted">
                            <?php echo htmlspecialchars($row['date']); ?><br>
                            <span style="font-size:0.78rem;"><?php echo htmlspecialchars($row['time']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo (int) $row['capacity']; ?></td>
                        <td>
                            <?php
                            $s     = strtolower($row['status']);
                            $badge = "badge-$s";
                            ?>
                            <span class="badge <?php echo htmlspecialchars($badge); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="edit_event.php?id=<?php echo (int) $row['event_id']; ?>" class="link-edit">✏️ Edit</a>
                                <a href="delete_event.php?id=<?php echo (int) $row['event_id']; ?>"
                                   class="link-delete delete-link"
                                   data-name="<?php echo htmlspecialchars($row['event_name']); ?>">
                                   🗑️ Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="no-results" style="display:none;text-align:center;padding:2rem;color:var(--text-muted);">
            No events match your search.
        </div>

        <?php else: ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No events yet</h3>
                <p>Get started by creating your first event.</p>
                <a href="create_event.php" class="btn btn-primary" id="first-event-btn">➕ Create First Event</a>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal-backdrop" id="delete-modal" style="display:none;">
        <div class="modal">
            <h2>🗑️ Delete Event</h2>
            <p id="delete-modal-text">Are you sure you want to delete this event? This action cannot be undone.</p>
            <div class="modal-actions">
                <a href="#" id="confirm-delete-btn" class="btn btn-danger">Yes, Delete</a>
                <button type="button" class="btn btn-secondary" id="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // ── Client-side table filter ─────────────────────────────
        const searchInput = document.getElementById('table-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q     = this.value.toLowerCase();
                const rows  = document.querySelectorAll('#events-tbody tr');
                let visible = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(q)) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';
            });
        }

        // ── Delete confirmation modal ────────────────────────────
        const modal      = document.getElementById('delete-modal');
        const confirmBtn = document.getElementById('confirm-delete-btn');
        const cancelBtn  = document.getElementById('cancel-delete-btn');
        const modalText  = document.getElementById('delete-modal-text');

        document.querySelectorAll('.delete-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const name = this.dataset.name;
                modalText.textContent = 'Delete "' + name + '"? This action cannot be undone.';
                confirmBtn.href = this.href;
                modal.style.display = 'flex';
            });
        });

        cancelBtn.addEventListener('click', () => { modal.style.display = 'none'; });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') modal.style.display = 'none';
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