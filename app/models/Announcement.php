<?php

class Announcement
{
    public function __construct(private mysqli $db)
    {
    }

    public function all(): mysqli_result
    {
        return $this->db->query('SELECT a.*,u.name author FROM announcements a LEFT JOIN users u ON u.user_id=a.created_by ORDER BY a.created_at DESC');
    }

    public function create(string $title, string $message, int $uid): void
    {
        $s = $this->db->prepare('INSERT INTO announcements(title,message,created_by) VALUES(?,?,?)');
        $s->bind_param('ssi', $title, $message, $uid);
        $s->execute();
        $s->close();
    }
}
?>