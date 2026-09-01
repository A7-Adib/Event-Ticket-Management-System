<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Controller.php';

foreach (glob(__DIR__ . '/../app/models/*.php') as $f) {
    require_once $f;
}

foreach (glob(__DIR__ . '/../app/controllers/*.php') as $f) {
    require_once $f;
}

Auth::start();

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
$base = trim(trim(BASE_PATH, '/'), '/');

if (str_starts_with($path, $base)) {
    $path = trim(substr($path, strlen($base)), '/');
}

$segments = $path === '' ? [] : explode('/', $path);
$method = $_SERVER['REQUEST_METHOD'];

$c = new DashboardController();

if ($path === '') {
    $c->index();
} elseif ($path === 'login') {
    (new AuthController())->login();
} elseif ($path === 'register') {
    (new AuthController())->register();
} elseif ($path === 'logout') {
    (new AuthController())->logout();
} elseif ($path === 'profile') {
    (new ProfileController())->show();
} elseif ($path === 'profile/edit') {
    (new ProfileController())->update();
} elseif ($path === 'events') {
    (new EventController())->browse();
} elseif ($segments[0] === 'event' && isset($segments[1])) {
    $_GET['id'] = (int)$segments[1];
    (new EventController())->details();
} elseif ($segments[0] === 'register-event' && isset($segments[1])) {
    $_GET['id'] = (int)$segments[1];
    (new EventController())->register();
} elseif ($path === 'organizer/events') {
    (new OrganizerController())->events();
} elseif ($path === 'organizer/create') {
    (new OrganizerController())->create();
} elseif ($segments[0] === 'organizer' && $segments[1] === 'edit' && isset($segments[2])) {
    $_GET['id'] = (int)$segments[2];
    (new OrganizerController())->edit();
} elseif ($segments[0] === 'organizer' && $segments[1] === 'delete' && isset($segments[2])) {
    $_GET['id'] = (int)$segments[2];
    (new OrganizerController())->delete();
} elseif ($path === 'organizer/participants') {
    (new OrganizerController())->participants();
} elseif ($path === 'organizer/participants/update') {
    (new OrganizerController())->updateParticipant();
} elseif ($path === 'ticket/generate' || ($segments[0] === 'ticket' && $segments[1] === 'generate' && isset($segments[2]))) {
    if (isset($segments[2])) {
        $_GET['registration_id'] = (int)$segments[2];
    }
    (new TicketController())->generate();
} elseif ($path === 'ticket/verify') {
    (new TicketController())->verify();
} elseif ($path === 'ticket/checkin' || ($segments[0] === 'ticket' && $segments[1] === 'checkin' && isset($segments[2]))) {
    if (isset($segments[2])) {
        $_GET['ticket'] = $segments[2];
    }
    (new TicketController())->checkin();
} elseif ($path === 'my-tickets') {
    (new TicketController())->myTickets();
} elseif ($path === 'announcements') {
    (new AnnouncementController())->index();
} elseif ($path === 'admin') {
    (new AdminController())->index();
} elseif ($path === 'admin/users') {
    (new AdminController())->users();
} elseif ($segments[0] === 'admin' && $segments[1] === 'edit' && isset($segments[2])) {
    $_GET['id'] = (int)$segments[2];
    (new AdminController())->edit();
} elseif ($segments[0] === 'admin' && $segments[1] === 'delete' && isset($segments[2])) {
    $_GET['id'] = (int)$segments[2];
    (new AdminController())->delete();
} else {
    http_response_code(404);
    echo '404 - Page not found';
}