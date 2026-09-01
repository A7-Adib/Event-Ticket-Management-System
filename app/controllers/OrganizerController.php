<?php

class OrganizerController extends Controller
{
    private Event $events;
    private Registration $regs;
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->events = new Event($this->db);
        $this->regs = new Registration($this->db);
    }

    public function events(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $result = Auth::role() === 'admin' ? $this->events->all() : $this->events->all(Auth::id());
        $this->render('organizer/events', compact('result'));
    }

    public function create(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $message = '';
        $categories = $this->events->categories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $d = $this->data();
            $d['organizer_id'] = Auth::id();

            if ($d['event_name'] === '' || $d['date'] === '' || $d['time'] === '' || $d['location'] === '' || $d['capacity'] < 1) {
                $message = 'Please fill all required fields.';
            } else {
                $this->events->create($d);
                $this->redirect('organizer/events');
            }
        }

        $this->render('organizer/form', compact('message', 'categories'));
    }

    public function edit(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $id = (int)($_GET['id'] ?? $_POST['event_id'] ?? 0);
        $event = $this->events->find($id);

        if (!$event) {
            exit('Event not found.');
        }

        if (Auth::role() === 'organizer' && !$this->events->belongsTo($id, Auth::id())) {
            exit('Access denied.');
        }

        $message = '';
        $categories = $this->events->categories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ((int)$_POST['capacity'] < 1) {
                $message = 'Capacity must be at least 1.';
            } else {
                $this->events->update($id, $this->data());
                $this->redirect('organizer/events');
            }
        }

        $this->render('organizer/form', compact('message', 'categories', 'event'));
    }

    private function data(): array
    {
        return [
            'event_name' => trim($_POST['event_name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date' => $_POST['date'] ?? '',
            'time' => $_POST['time'] ?? '',
            'location' => trim($_POST['location'] ?? ''),
            'capacity' => (int)($_POST['capacity'] ?? 0),
            'status' => $_POST['status'] ?? 'Upcoming',
            'category_id' => (int)($_POST['category_id'] ?? 0)
        ];
    }

    public function delete(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $id = (int)($_GET['id'] ?? 0);

        if (Auth::role() === 'organizer' && !$this->events->belongsTo($id, Auth::id())) {
            exit('Access denied.');
        }

        $this->events->delete($id);
        $this->redirect('organizer/events');
    }

    public function participants(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $result = Auth::role() === 'admin' ? $this->regs->all() : $this->regs->forOrganizer(Auth::id());
        $this->render('organizer/participants', compact('result'));
    }

    public function updateParticipant(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $id = (int)($_POST['registration_id'] ?? 0);
        $status = $_POST['status'] ?? 'Registered';

        if (!in_array($status, ['Registered', 'Confirmed', 'Attended', 'Cancelled'], true)) {
            $status = 'Registered';
        }

        $this->regs->updateStatus($id, $status);
        $this->redirect('organizer/participants');
    }
}
?>