# Event Ticket Management System — MVC Final

A complete PHP + MySQL MVC event management, registration, ticketing and staff check-in system designed for XAMPP.

## 1. Folder structure

```text
Event-Ticket-Management-System/
├── app/
│   ├── config/config.php
│   ├── core/                 # Database, authentication, base controller, helpers
│   ├── controllers/          # Request/business logic
│   ├── models/               # Database models
│   └── views/                # Presentation templates
├── database/setup.sql        # Clean schema + demo data
├── public/
│   ├── index.php             # MVC front controller
│   └── assets/               # CSS and event images
├── index.php                 # Convenience entry point
└── .htaccess                 # Apache rewrite + app protection
```

## 2. XAMPP installation

1. Extract this folder to:
   `C:\xampp\htdocs\Event-Ticket-Management-System`
2. Start **Apache** and **MySQL** from XAMPP.
3. Open `http://localhost/phpmyadmin/`.
4. Import `database/setup.sql`.
5. Open:
   `http://localhost/Event-Ticket-Management-System/`

If your MySQL root account has a password, update `app/config/config.php`:

```php
const DB_USER = 'root';
const DB_PASS = 'YOUR_PASSWORD';
```

The default XAMPP configuration uses a blank root password.

## 3. Demo accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@eventflow.local | admin123 |
| Organizer | organizer@eventflow.local | organizer123 |
| Participant | participant@eventflow.local | participant123 |
| Staff | staff@eventflow.local | staff123 |

Passwords are stored with PHP `password_hash()` compatible bcrypt hashes.

## 4. Complete user flow

### Participant
Register → Login → Browse Events → Event Details → Register → Wait for organizer confirmation → Receive ticket → My Tickets.

### Organizer
Login → Create Event → Edit/Delete own events → View participants → Confirm registration → Generate ticket.

### Staff
Login → Verify ticket → Proceed to check-in → Check-in changes ticket to `Used` and registration to `Attended`.

### Admin
Login → Admin Panel → Manage users/roles → View system counts → Manage events → Access organizer/participant/staff functions.

## 5. Important rules

- Public registration always creates a **Participant** account.
- Only Admin can change user roles.
- Organizers can edit/delete only their own events.
- Admin can manage all events and users.
- A participant cannot register twice for the same active event.
- A cancelled registration can be registered again if seats are available.
- Tickets can only be generated after the organizer confirms the registration.
- Each registration can have only one ticket.
- A valid ticket can be checked in only once.
- Check-in is transactional: ticket, check-in record and registration status are updated together.
- App internals and SQL files are blocked by Apache rewrite rules.

## 6. Main routes

```text
/                         Dashboard
/login                    Login
/register                 Participant registration
/logout                   Logout
/profile                  Profile
/profile/edit             Update profile
/events                   Browse events
/event/{id}               Event details
/register-event/{id}     Register for event

/organizer/events         Manage events
/organizer/create         Create event
/organizer/edit/{id}      Edit event
/organizer/delete/{id}    Delete event
/organizer/participants   Manage registrations

/ticket/generate/{id}     Generate ticket
/ticket/verify            Verify ticket
/ticket/checkin           Check-in ticket
/my-tickets               Participant tickets

/announcements            Announcements
/admin                    Admin dashboard
/admin/users              User management
/admin/edit/{id}          Edit user
/admin/delete/{id}        Delete user
```

## 7. Final verification checklist

- PHP syntax checked for every PHP file.
- Demo password hashes verified with `password_verify()`.
- One database configuration is used throughout the MVC application.
- CSS is centralized in `public/assets/css/style.css`.
- Event images are served through the MVC asset helper.
- Responsive navigation and layouts are included.
- Tables scroll horizontally on small screens.
- Forms, buttons, cards, alerts, badges and ticket panels share one visual system.
