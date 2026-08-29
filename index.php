<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Event Ticket Management System — Create, manage, and register for events seamlessly.">

    <title>EventFlow — Event Ticket Management System</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

    <?php
    $base = '';
    $active = 'home';
    require 'includes/nav.php';
    ?>

    <div class="page-wrapper">

        <section class="hero">

            <div class="hero-badge">
                Event Ticket Management System
            </div>

            <h1>
                Manage Events.<br>
                Register Instantly.
            </h1>

            <p class="hero-desc">
                A powerful platform for organizers to create and manage
                events, and for participants to discover and register
                in seconds. All in one beautiful dashboard.
            </p>

            <div class="hero-actions">

                <a href="organizer/view_events.php"
                   class="btn btn-primary btn-lg"
                   id="organizer-btn">

                     Organizer Portal

                </a>

                <a href="participant/events.php"
                   class="btn btn-secondary btn-lg"
                   id="participant-btn">

                    Browse Events

                </a>

            </div>

        </section>


        <div class="section-title">
            What's included
        </div>

        <div class="feature-grid" id="features">

            <div class="feature-item">

                <div class="feature-item-icon">
                    
                </div>

                <div>

                    <h4>
                        Create Events
                    </h4>

                    <p>
                        Add events with full details including
                        date, time, location &amp; capacity.
                    </p>

                </div>

            </div>


            <div class="feature-item">

                <div class="feature-item-icon">
                    
                </div>

                <div>

                    <h4>
                        Manage &amp; Edit
                    </h4>

                    <p>
                        Update event information, change statuses,
                        and keep records current.
                    </p>

                </div>

            </div>


            <div class="feature-item">

                <div class="feature-item-icon">
                    
                </div>

                <div>

                    <h4>
                        Participant Tracking
                    </h4>

                    <p>
                        View all registrations and update
                        participant statuses in real time.
                    </p>

                </div>

            </div>


            <div class="feature-item">

                <div class="feature-item-icon">
                    
                </div>

                <div>

                    <h4>
                        Smart Event Search
                    </h4>

                    <p>
                        Search and filter events by name,
                        category, date, or location instantly.
                    </p>

                </div>

            </div>
            <div class="section-title">Choose your portal</div>

<div class="portal-grid">

    <div class="portal-card organizer" id="card-organizer"
         role="button" tabindex="0">

        <div class="portal-icon"></div>

        <h2>Organizer</h2>

        <p>
            Full control over event creation, management,
            and participant oversight. Perfect for event
            coordinators and administrators.
        </p>

        <div class="portal-links">

            <a href="organizer/view_events.php"
               class="portal-link"
               id="link-view-events">
                <span>View All Events</span>
                <span class="portal-link-arrow">→</span>
            </a>

            <a href="organizer/create_event.php"
               class="portal-link"
               id="link-create-event">
                <span>Create New Event</span>
                <span class="portal-link-arrow">→</span>
            </a>

            <a href="organizer/participants.php"
               class="portal-link"
               id="link-participants">
                <span>Manage Participants</span>
                <span class="portal-link-arrow">→</span>
            </a>

        </div>
    </div>


    <div class="portal-card participant" id="card-participant"
         role="button" tabindex="0">

        <div class="portal-icon"></div>

        <h2>Participant</h2>

        <p>
            Discover upcoming events, view full event details,
            and register your attendance seamlessly from one place.
        </p>

        <div class="portal-links">

            <a href="participant/events.php"
               class="portal-link"
               id="link-browse-events">
                <span>Browse Events</span>
                <span class="portal-link-arrow">→</span>
            </a>

        </div>
    </div>

</div>

        </div>

    </div>

    <?php require 'includes/footer.php'; ?>
    <script>
        // ── Hamburger toggle ─────────────────────────────────────
        const toggle   = document.getElementById('nav-toggle');
        const navLinks = document.getElementById('nav-links');
        toggle.addEventListener('click', function() {
            this.classList.toggle('open');
            navLinks.classList.toggle('open');
        });

        // ── Portal card click navigation ─────────────────────────
        document.getElementById('card-organizer').addEventListener('click', function(e) {
            if (!e.target.closest('a')) window.location.href = 'organizer/view_events.php';
        });
        document.getElementById('card-participant').addEventListener('click', function(e) {
            if (!e.target.closest('a')) window.location.href = 'participant/events.php';
        });

        // Keyboard nav for portal cards
        document.querySelectorAll('.portal-card').forEach(card => {
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') card.click();
            });
        });

        // ── Animate feature items on scroll ──────────────────────
        document.querySelectorAll('.feature-item').forEach(item => {
            item.style.opacity   = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        });

        function animateOnScroll() {
            document.querySelectorAll('.feature-item').forEach((item, i) => {
                if (item.getBoundingClientRect().top < window.innerHeight - 60) {
                    item.style.transitionDelay = (i * 0.06) + 's';
                    item.style.opacity   = '1';
                    item.style.transform = 'translateY(0)';
                }
            });
        }

        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll();
    </script>

</body>

</html>