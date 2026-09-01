<?php

class Registration
{
    public function __construct(private mysqli $db)
    {
    }

    public function register(int $uid, int $eid): string
    {
        $s = $this->db->prepare("SELECT r.registration_id,r.status FROM registrations r WHERE r.user_id=? AND r.event_id=?");
        $s->bind_param('ii', $uid, $eid);
        $s->execute();
        $old = $s->get_result()->fetch_assoc();
        $s->close();

        if ($old && $old['status'] !== 'Cancelled') {
            return 'duplicate';
        }

        $s = $this->db->prepare("SELECT capacity,(SELECT COUNT(*) FROM registrations r WHERE r.event_id=events.event_id AND r.status<>'Cancelled') cnt FROM events WHERE event_id=? AND status='Upcoming'");
        $s->bind_param('i', $eid);
        $s->execute();
        $e = $s->get_result()->fetch_assoc();
        $s->close();

        if (!$e) {
            return 'missing';
        }

        if ((int)$e['cnt'] >= (int)$e['capacity']) {
            return 'full';
        }

        if ($old) {
            $s = $this->db->prepare("UPDATE registrations SET status='Registered',registration_date=CURRENT_TIMESTAMP WHERE registration_id=?");
            $s->bind_param('i', $old['registration_id']);
            $s->execute();
            $s->close();
            return 'ok';
        }

        $s = $this->db->prepare("INSERT INTO registrations(user_id,event_id,status) VALUES(?,?, 'Registered')");
        $s->bind_param('ii', $uid, $eid);
        $s->execute();
        $s->close();
        return 'ok';
    }

    public function forOrganizer(int $oid): mysqli_result
    {
        $s = $this->db->prepare("SELECT r.*,u.name attendee_name,u.email,e.event_name,e.date,e.event_id,t.ticket_code,t.status ticket_status FROM registrations r JOIN users u ON u.user_id=r.user_id JOIN events e ON e.event_id=r.event_id LEFT JOIN tickets t ON t.registration_id=r.registration_id WHERE e.organizer_id=? ORDER BY r.registration_date DESC");
        $s->bind_param('i', $oid);
        $s->execute();
        $r = $s->get_result();
        $s->close();
        return $r;
    }

    public function forUser(int $uid): mysqli_result
    {
        $s = $this->db->prepare("SELECT r.*,e.event_name,e.date,e.time,e.location,t.ticket_code,t.status ticket_status FROM registrations r JOIN events e ON e.event_id=r.event_id LEFT JOIN tickets t ON t.registration_id=r.registration_id WHERE r.user_id=? ORDER BY r.registration_date DESC");
        $s->bind_param('i', $uid);
        $s->execute();
        $r = $s->get_result();
        $s->close();
        return $r;
    }

    public function all(): mysqli_result
    {
        return $this->db->query("SELECT r.*,u.name attendee_name,u.email,e.event_name,e.date,e.event_id,t.ticket_code,t.status ticket_status FROM registrations r JOIN users u ON u.user_id=r.user_id JOIN events e ON e.event_id=r.event_id LEFT JOIN tickets t ON t.registration_id=r.registration_id ORDER BY r.registration_date DESC");
    }

    public function updateStatus(int $id, string $status): void
    {
        $s = $this->db->prepare('UPDATE registrations SET status=? WHERE registration_id=?');
        $s->bind_param('si', $status, $id);
        $s->execute();
        $s->close();
    }

    public function find(int $id, bool $admin = false, int $oid = 0): ?array
    {
        $sql = "SELECT r.*,u.name attendee_name,u.email,e.event_id,e.event_name,e.organizer_id FROM registrations r JOIN users u ON u.user_id=r.user_id JOIN events e ON e.event_id=r.event_id WHERE r.registration_id=?" . ($admin ? '' : ' AND e.organizer_id=?');
        $s = $this->db->prepare($sql);
        $admin ? $s->bind_param('i', $id) : $s->bind_param('ii', $id, $oid);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();
        return $r ?: null;
    }
}
?>