<?php

function e(mixed $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function url(string $route = ''): string
{
    return BASE_PATH . ltrim($route, '/');
}

function asset(string $path): string
{
    return BASE_PATH . 'assets/' . ltrim($path, '/');
}

function flash_message(): ?array
{
    Auth::start();
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function active(string $route): string
{
    $current = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
    $base = trim(trim(BASE_PATH, '/'), '/');

    if ($base !== '' && str_starts_with($current, $base)) {
        $current = trim(substr($current, strlen($base)), '/');
    }

    return $current == trim($route, '/') ? 'active' : '';
}

function event_image(int $id): string
{
    $n = (($id - 1) % 7) + 1;
    return asset('image/event' . $n . '.png');
}
?>