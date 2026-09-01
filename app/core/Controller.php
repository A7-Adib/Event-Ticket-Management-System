<?php
declare(strict_types=1);

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect(string $route = ''): never
    {
        header('Location: ' . BASE_PATH . ltrim($route, '/'));
        exit;
    }

    protected function flash(string $type, string $message): void
    {
        Auth::start();
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message
        ];
    }
}
?>