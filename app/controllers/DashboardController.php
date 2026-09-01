<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        $db = Database::connection();
        Auth::start();
        $events = (new Event($db))->upcoming();
        $role = Auth::role();
        $stats = [];
        $id = Auth::id();

        if ($role === 'admin') {
            foreach (['users', 'events', 'registrations', 'tickets', 'check_in'] as $t) {
                $r = $db->query("SELECT COUNT(*) c FROM `$t`")->fetch_assoc();
                $stats[$t] = (int)$r['c'];
            }
        } elseif ($role === 'organizer') {
            $s = $db->prepare('SELECT COUNT(*) c FROM events WHERE organizer_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $stats['events'] = (int)$s->get_result()->fetch_assoc()['c'];
            $s->close();

            $s = $db->prepare('SELECT COUNT(*) c FROM registrations r JOIN events e ON e.event_id=r.event_id WHERE e.organizer_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $stats['registrations'] = (int)$s->get_result()->fetch_assoc()['c'];
            $s->close();
        } elseif ($role === 'participant') {
            $s = $db->prepare('SELECT COUNT(*) c FROM registrations WHERE user_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $stats['registrations'] = (int)$s->get_result()->fetch_assoc()['c'];
            $s->close();

            $s = $db->prepare('SELECT COUNT(*) c FROM tickets WHERE user_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $stats['tickets'] = (int)$s->get_result()->fetch_assoc()['c'];
            $s->close();
        }

        $this->render('dashboard/index', compact('events', 'role', 'stats'));
    }
}
?>