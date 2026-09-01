<?php
declare(strict_types=1);

class Event
{
    public function __construct(private mysqli $db)
    {
    }

    private string $select = "SELECT e.*,u.name organizer_name,c.category_name,(SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.event_id AND r.status<>'Cancelled') registered_count FROM events e JOIN users u ON u.user_id=e.organizer_id LEFT JOIN categories c ON c.category_id=e.category_id";

    public function all(?int $organizerId = null): mysqli_result
    {
        if ($organizerId !== null) {
            $s = $this->db->prepare($this->select . ' WHERE e.organizer_id=? ORDER BY e.date,e.time,e.event_id');
            $s->bind_param('i', $organizerId);
            $s->execute();
            $r = $s->get_result();
            $s->close();
            return $r;
        }

        return $this->db->query($this->select . ' ORDER BY e.date,e.time,e.event_id');
    }

    public function upcoming(int $limit = 8): mysqli_result
    {
        $s = $this->db->prepare($this->select . " WHERE e.status<>'Cancelled' ORDER BY e.date,e.time,e.event_id LIMIT ?");
        $s->bind_param('i', $limit);
        $s->execute();
        $r = $s->get_result();
        $s->close();
        return $r;
    }

    public function find(int $id): ?array
    {
        $s = $this->db->prepare($this->select . ' WHERE e.event_id=?');
        $s->bind_param('i', $id);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();
        return $r ?: null;
    }

    public function categories(): mysqli_result
    {
        return $this->db->query('SELECT category_id,category_name FROM categories ORDER BY category_name');
    }

    public function create(array $d): void
    {
        $s = $this->db->prepare('INSERT INTO events(event_name,description,date,time,location,capacity,status,organizer_id,category_id) VALUES(?,?,?,?,?,?,?,?,?)');
        $s->bind_param('sssssisii', $d['event_name'], $d['description'], $d['date'], $d['time'], $d['location'], $d['capacity'], $d['status'], $d['organizer_id'], $d['category_id']);
        $s->execute();
        $s->close();
    }

    public function update(int $id, array $d): void
    {
        $s = $this->db->prepare('UPDATE events SET event_name=?,description=?,date=?,time=?,location=?,capacity=?,status=?,category_id=? WHERE event_id=?');
        $s->bind_param('sssssisii', $d['event_name'], $d['description'], $d['date'], $d['time'], $d['location'], $d['capacity'], $d['status'], $d['category_id'], $id);
        $s->execute();
        $s->close();
    }

    public function delete(int $id): void
    {
        $s = $this->db->prepare('DELETE FROM events WHERE event_id=?');
        $s->bind_param('i', $id);
        $s->execute();
        $s->close();
    }

    public function belongsTo(int $id, int $organizer): bool
    {
        $s = $this->db->prepare('SELECT event_id FROM events WHERE event_id=? AND organizer_id=?');
        $s->bind_param('ii', $id, $organizer);
        $s->execute();
        $ok = $s->get_result()->num_rows > 0;
        $s->close();
        return $ok;
    }
}
?>