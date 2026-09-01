<?php

class Ticket
{
    public function __construct(private mysqli $db)
    {
    }

    public function generate(array $r, string $type): string
    {
        $s = $this->db->prepare('SELECT ticket_code FROM tickets WHERE registration_id=?');
        $s->bind_param('i', $r['registration_id']);
        $s->execute();
        $old = $s->get_result()->fetch_assoc();
        $s->close();

        if ($old) {
            return $old['ticket_code'];
        }

        do {
            $code = 'TKT' . strtoupper(bin2hex(random_bytes(4)));
            $s = $this->db->prepare('SELECT id FROM tickets WHERE ticket_code=?');
            $s->bind_param('s', $code);
            $s->execute();
            $taken = $s->get_result()->num_rows > 0;
            $s->close();
        } while ($taken);

        $s = $this->db->prepare("INSERT INTO tickets(ticket_code,user_id,event_id,registration_id,attendee_name,event_name,ticket_type,status) VALUES(?,?,?,?,?,?,?,'Valid')");
        $s->bind_param('siiisss', $code, $r['user_id'], $r['event_id'], $r['registration_id'], $r['attendee_name'], $r['event_name'], $type);
        $s->execute();
        $s->close();

        return $code;
    }

    public function findByCode(string $code): ?array
    {
        $s = $this->db->prepare("SELECT t.*,r.status registration_status,e.date,e.time,e.location FROM tickets t LEFT JOIN registrations r ON r.registration_id=t.registration_id LEFT JOIN events e ON e.event_id=t.event_id WHERE t.ticket_code=? LIMIT 1");
        $s->bind_param('s', $code);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();

        return $r ?: null;
    }

    public function forUser(int $uid): mysqli_result
    {
        $s = $this->db->prepare('SELECT t.*,e.date,e.time,e.location FROM tickets t JOIN events e ON e.event_id=t.event_id WHERE t.user_id=? ORDER BY t.created_at DESC');
        $s->bind_param('i', $uid);
        $s->execute();
        $r = $s->get_result();
        $s->close();

        return $r;
    }

    public function checkIn(array $t): string
    {
        $this->db->begin_transaction();

        try {
            $s = $this->db->prepare('SELECT id FROM check_in WHERE ticket_id=?');
            $s->bind_param('i', $t['id']);
            $s->execute();
            $exists = $s->get_result()->num_rows > 0;
            $s->close();

            if ($exists) {
                $this->db->rollback();
                return 'duplicate';
            }

            if ($t['status'] !== 'Valid') {
                $this->db->rollback();
                return strtolower($t['status']) === 'cancelled' ? 'cancelled' : 'used';
            }

            $s = $this->db->prepare("INSERT INTO check_in(ticket_id,ticket_code,attendee_name,status) VALUES(?,?,?,'Checked-In')");
            $s->bind_param('iss', $t['id'], $t['ticket_code'], $t['attendee_name']);
            $s->execute();
            $s->close();

            $s = $this->db->prepare("UPDATE tickets SET status='Used' WHERE id=? AND status='Valid'");
            $s->bind_param('i', $t['id']);
            $s->execute();
            $s->close();

            $s = $this->db->prepare("UPDATE registrations SET status='Attended' WHERE registration_id=? AND status<>'Cancelled'");
            $s->bind_param('i', $t['registration_id']);
            $s->execute();
            $s->close();

            $this->db->commit();
            return 'ok';
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>