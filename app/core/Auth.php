<?php
declare(strict_types=1);

class Auth
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function login(array $u): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$u['user_id'];
        $_SESSION['name'] = $u['name'];
        $_SESSION['email'] = $u['email'];
        $_SESSION['role'] = self::normalizeRole($u['role']);
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires'  => time() - 42000,
                    'path'     => $p['path'],
                    'domain'   => $p['domain'],
                    'secure'   => $p['secure'],
                    'httponly' => $p['httponly'],
                    'samesite' => $p['samesite'] ?? 'Lax'
                ]
            );
        }

        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function id(): int
    {
        self::start();
        return (int)($_SESSION['user_id'] ?? 0);
    }

    public static function role(): string
    {
        self::start();
        return self::normalizeRole((string)($_SESSION['role'] ?? ''));
    }

    public static function normalizeRole(string $r): string
    {
        $r = strtolower(trim($r));
        return $r === 'stuff' ? 'staff' : $r;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_PATH . 'login');
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        if (!in_array(self::role(), array_map('strtolower', $roles), true)) {
            http_response_code(403);
            exit('Access denied.');
        }
    }
}
?>