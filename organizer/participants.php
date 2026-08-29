<?php
require_once '../config/database.php';

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$sql = "SELECT 
            registrations.registration_id,
            registrations.user_id,
            users.name,
            events.event_name,
            registrations.registration_date,
            registrations.status
        FROM registrations
        JOIN users 
            ON registrations.user_id = users.user_id
        JOIN events 
            ON registrations.event_id = events.event_id";

if ($search != "") {
    $search_value = "%" . $search . "%";
    $sql .= " WHERE users.name LIKE ?
              OR events.event_name LIKE ?
              OR registrations.status LIKE ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_value, $search_value, $search_value);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY registrations.registration_id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage registered participants on EventFlow.">
    <title>Participants — EventFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php $base = '../'; $active = 'participants'; require '../includes/nav.php'; ?>

    <div class="page-wrapper">

        <div class="page-header">
            <div class="header-row">
                <h1>Registered Participants</h1>
            </div>
            <p>Search, view, and update the status of all registered participants.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" id="search-form">
            <div class="search-bar">
                <input type="text"
                       id="search-input"
                       name="search"
                       placeholder="🔍  Search by name, event or status…"
                       value="<?php echo htmlspecialchars($search); ?>"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary" id="search-btn">Search</button>
                <?php if ($search): ?>
                <a href="participants.php" class="btn btn-secondary" id="clear-btn">✕ Clear</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($search): ?>
        <div class="alert alert-info" id="search-alert">
            <span class="alert-icon">🔍</span>
            Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong>
        </div>
        <?php endif; ?>

        <!-- Table -->
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-wrapper">
            <table id="participants-table">
                <thead>
                    <tr>
                        <th>Reg. ID</th>
                        <th>Participant</th>
                        <th>Event</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="participants-tbody">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="muted">#<?php echo (int) $row['registration_id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                            <span style="font-size:0.78rem;color:var(--text-muted);">User #<?php echo (int) $row['user_id']; ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                        <td class="muted"><?php echo htmlspecialchars($row['registration_date']); ?></td>
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
                                <a href="update_participant.php?id=<?php echo (int) $row['registration_id']; ?>" class="link-update">
                                    🔄 Update Status
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php else: ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h3><?php echo $search ? 'No results found' : 'No participants yet'; ?></h3>
                <p><?php echo $search ? 'Try a different search term.' : 'Participants will appear here once they register for events.'; ?></p>
                <?php if ($search): ?>
                <a href="participants.php" class="btn btn-secondary" id="clear-search-btn">✕ Clear Search</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <script>
        // ── Live search with debounce ────────────────────────────
        const searchInput = document.getElementById('search-input');
        const searchForm  = document.getElementById('search-form');
        let debounceTimer;

        if (searchInput) {
            // Submit form on Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });

            // Clear button visual feedback
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                // Auto-submit after 600ms of no typing (debounced)
                debounceTimer = setTimeout(() => {
                    if (this.value.trim() !== '<?php echo addslashes($search); ?>') {
                        searchForm.submit();
                    }
                }, 600);
            });
        }

        // ── Row highlight on hover ───────────────────────────────
        document.querySelectorAll('#participants-tbody tr').forEach(row => {
            row.style.cursor = 'default';
        });

        // ── Auto-dismiss search alert ─────────────────────────────
        const searchAlert = document.getElementById('search-alert');
        if (searchAlert) {
            setTimeout(() => {
                searchAlert.style.transition = 'opacity 0.4s ease';
                searchAlert.style.opacity = '0';
                setTimeout(() => searchAlert.remove(), 400);
            }, 4000);
        }

        // ── Confirm search on submit ─────────────────────────────
        const searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                this.textContent = '⏳ Searching…';
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