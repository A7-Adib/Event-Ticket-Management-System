<?php

class TicketController extends Controller
{
    private Ticket $tickets;
    private Registration $regs;

    public function __construct()
    {
        $db = Database::connection();
        $this->tickets = new Ticket($db);
        $this->regs = new Registration($db);
    }

    public function generate(): void
    {
        Auth::requireRole(['organizer', 'admin']);
        $rid = (int)($_GET['registration_id'] ?? $_POST['registration_id'] ?? 0);
        $reg = $rid ? $this->regs->find($rid, Auth::role() === 'admin', Auth::id()) : null;
        $registrations = null;

        if (!$reg) {
            $db = Database::connection();
            $sql = "SELECT r.registration_id, u.name attendee_name, e.event_name 
                    FROM registrations r 
                    JOIN users u ON u.user_id = r.user_id 
                    JOIN events e ON e.event_id = r.event_id 
                    LEFT JOIN tickets t ON t.registration_id = r.registration_id 
                    WHERE t.id IS NULL AND r.status = 'Confirmed'" . 
                    (Auth::role() === 'admin' ? '' : ' AND e.organizer_id = ?') . 
                    " ORDER BY r.registration_date DESC";

            $s = $db->prepare($sql);
            if (Auth::role() !== 'admin') {
                $s->bind_param('i', Auth::id());
            }
            $s->execute();
            $registrations = $s->get_result();
            $s->close();
        }

        $message = '';
        $type = 'error';
        $code = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reg) {
            if ($reg['status'] !== 'Confirmed') {
                $message = 'Registration must be confirmed before a ticket can be generated.';
                $type = 'warning';
                $this->render('tickets/generate', compact('reg', 'registrations', 'message', 'type', 'code'));
                return;
            }

            $ticketType = $_POST['ticket_type'] ?? 'Regular';
            if (!in_array($ticketType, ['Regular', 'VIP', 'Student'], true)) {
                $ticketType = 'Regular';
            }

            $code = $this->tickets->generate($reg, $ticketType);
            $message = 'Ticket generated successfully: ' . $code;
            $type = 'success';
        }

        $this->render('tickets/generate', compact('reg', 'registrations', 'message', 'type', 'code'));
    }

    public function verify(): void
    {
        Auth::requireRole(['staff', 'admin']);
        $code = trim($_POST['ticket_code'] ?? '');
        $ticket = null;
        $message = '';
        $type = 'error';

        if ($code !== '') {
            $ticket = $this->tickets->findByCode($code);
            if (!$ticket) {
                $message = 'Invalid ticket code.';
            } elseif ($ticket['status'] === 'Valid') {
                $message = 'Valid ticket — ready for check-in.';
                $type = 'success';
            } elseif ($ticket['status'] === 'Used') {
                $message = 'This ticket has already been used.';
                $type = 'warning';
            } else {
                $message = 'This ticket is ' . $ticket['status'] . '.';
            }
        }

        $this->render('tickets/verify', compact('ticket', 'message', 'type', 'code'));
    }

    public function checkin(): void
    {
        Auth::requireRole(['staff', 'admin']);
        $code = trim($_GET['ticket'] ?? $_POST['ticket_code'] ?? '');
        $message = '';
        $type = 'error';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['ticket'])) {
            if ($code === '') {
                $message = 'Enter a ticket code.';
            } else {
                $t = $this->tickets->findByCode($code);
                if (!$t) {
                    $message = 'Invalid ticket code.';
                } else {
                    $r = $this->tickets->checkIn($t);
                    $map = [
                        'ok' => ['success', 'Check-in successful! Welcome ' . $t['attendee_name'] . '.'],
                        'duplicate' => ['warning', 'Duplicate check-in prevented.'],
                        'used' => ['warning', 'This ticket has already been used.'],
                        'cancelled' => ['error', 'This ticket has been cancelled.']
                    ];
                    [$type, $message] = $map[$r];
                }
            }
        }

        $this->render('tickets/checkin', compact('message', 'type', 'code'));
    }

    public function myTickets(): void
    {
        Auth::requireRole(['participant', 'admin']);
        $tickets = $this->tickets->forUser(Auth::id());
        $this->render('participant/tickets', compact('tickets'));
    }
}
?>