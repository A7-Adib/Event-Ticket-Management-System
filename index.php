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

    </div>

    <?php require 'includes/footer.php'; ?>

</body>

</html>