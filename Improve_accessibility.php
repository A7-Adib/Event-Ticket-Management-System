<!DOCTYPE html>
<html>

<head>
    <title>Event Management System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            text-align: center;
            margin: 0;
        }

        .container {
            width: 500px;
            max-width: 90%;
            margin: 80px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            box-sizing: border-box;
        }

        h1 {
            margin-bottom: 10px;
        }

        .menu {
            margin-top: 25px;
        }

        a {
            display: block;
            margin: 15px;
            padding: 12px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background: #555;
        }

        a:focus {
            outline: 3px solid #777;
            outline-offset: 2px;
        }

        @media (max-width: 600px) {

            .container {
                margin: 40px auto;
                padding: 20px;
            }

            h1 {
                font-size: 26px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>

</head>

<body>

<div class="container">

    <h1>Event Management System</h1>

    <h2>Person 4 — Samia</h2>

    <nav class="menu" aria-label="Main navigation">

        <a href="generate_ticket.php">
            Ticket Management
        </a>

        <a href="verify_ticket.php">
            Verify Ticket
        </a>

        <a href="checkin.php">
            Event Staff Check-in
        </a>

        <a href="add_announcement.php">
            Add Announcement
        </a>

        <a href="announcements.php">
            View Announcements
        </a>

    </nav>

</div>

</body>

</html>