<footer class="site-footer">
        <div class="footer-inner">
            <div>
                <a class="brand" href="<?= url('') ?>">
                    <span class="brand-icon">🎫</span> EventFlow
                </a>
                <p>Event management &amp; ticketing system.</p>
            </div>
            <div>
                <h4>Quick Links</h4>
                <p>
                    <a href="<?= url('events') ?>">Events</a> · 
                    <a href="<?= url('announcements') ?>">Announcements</a>
                </p>
            </div>
        </div>
        <div class="footer-bottom">© <?= date('Y') ?> EventFlow</div>
    </footer>

    <script>
        const t = document.getElementById('nav-toggle'),
              n = document.getElementById('nav-links');
        if (t && n) {
            t.onclick = () => n.classList.toggle('open');
        }
    </script>
</body>
</html>