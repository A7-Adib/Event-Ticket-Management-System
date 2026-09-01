<?php

class EventController extends Controller
{
    private Event $events;
    private Registration $regs;

    public function __construct()
    {
        $db = Database::connection();
        $this->events = new Event($db);
        $this->regs = new Registration($db);
    }

    public function details(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $event = $this->events->find($id);

        if (!$event) {
            http_response_code(404);
            exit('Event not found.');
        }

        $this->render('events/details', compact('event'));
    }

    public function browse(): void
    {
        Auth::requireRole(['participant', 'admin']);
        $events = $this->events->all();
        $this->render('participant/events', compact('events'));
    }

    public function register(): void
    {
        Auth::requireRole(['participant', 'admin']);
        $eid = (int)($_GET['id'] ?? $_POST['event_id'] ?? 0);
        $event = $this->events->find($eid);

        if (!$event) {
            exit('Event not found.');
        }

        $msg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $r = $this->regs->register(Auth::id(), $eid);
            $map = [
                'ok' => ['success', 'Registration successful.'],
                'duplicate' => ['warning', 'You are already registered for this event.'],
                'full' => ['error', 'This event is full.'],
                'missing' => ['error', 'Event not found.'],
                'cancelled' => ['error', 'Your previous registration was cancelled; contact the organizer.']
            ];
            [$type, $msg] = $map[$r];
        }

        $this->render('participant/register', compact('event', 'msg', 'type'));
    }
}
?>