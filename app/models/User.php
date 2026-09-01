<?php

class User
{
    public function __construct(private mysqli $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $s = $this->db->prepare('SELECT user_id,name,email,password,phone,role FROM users WHERE email=? LIMIT 1');
        $s->bind_param('s', $email);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();
        return $r ?: null;
    }

    public function find(int $id): ?array
    {
        $s = $this->db->prepare('SELECT user_id,name,email,password,phone,role FROM users WHERE user_id=?');
        $s->bind_param('i', $id);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();
        return $r ?: null;
    }

    public function emailExists(string $email, int $except = 0): bool
    {
        $s = $this->db->prepare('SELECT user_id FROM users WHERE email=? AND user_id<>? LIMIT 1');
        $s->bind_param('si', $email, $except);
        $s->execute();
        $x = $s->get_result()->num_rows > 0;
        $s->close();
        return $x;
    }

    public function create(string $name, string $email, string $hash, string $phone, string $role = 'Participant'): int
    {
        $s = $this->db->prepare('INSERT INTO users(name,email,password,phone,role) VALUES(?,?,?,?,?)');
        $s->bind_param('sssss', $name, $email, $hash, $phone, $role);
        $s->execute();
        $id = $this->db->insert_id;
        $s->close();
        return $id;
    }

    public function all(): mysqli_result
    {
        return $this->db->query('SELECT user_id,name,email,phone,role,created_at FROM users ORDER BY user_id DESC');
    }

    public function update(int $id, string $name, string $email, string $phone, string $role): void
    {
        $s = $this->db->prepare('UPDATE users SET name=?,email=?,phone=?,role=? WHERE user_id=?');
        $s->bind_param('ssssi', $name, $email, $phone, $role, $id);
        $s->execute();
        $s->close();
    }

    public function delete(int $id): void
    {
        $this->db->begin_transaction();
        try {
            $s = $this->db->prepare('DELETE FROM events WHERE organizer_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $s->close();

            $s = $this->db->prepare('DELETE FROM users WHERE user_id=?');
            $s->bind_param('i', $id);
            $s->execute();
            $s->close();

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>