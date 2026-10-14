<?php
// Helper bersama untuk halaman dan handler aplikasi.

function ensure_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    $projectRoot = realpath(__DIR__ . '/..');
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    $base = '';

    if ($projectRoot !== false && $documentRoot !== false) {
        $projectPath = str_replace('\\', '/', $projectRoot);
        $documentPath = rtrim(str_replace('\\', '/', $documentRoot), '/');
        if (str_starts_with($projectPath, $documentPath . '/')) {
            $base = substr($projectPath, strlen($documentPath));
        }
    }

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . app_url($path));
    exit;
}

function set_flash(string $type, string $message): void
{
    ensure_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function pull_flash(): ?array
{
    ensure_session();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}

function valid_positive_int(mixed $value): ?int
{
    if (!is_string($value) && !is_int($value)) {
        return null;
    }

    $value = (string) $value;
    if (!preg_match('/^[1-9][0-9]*$/', $value)) {
        return null;
    }

    $number = filter_var($value, FILTER_VALIDATE_INT);
    return $number === false ? null : $number;
}

function valid_nonnegative_int(mixed $value): ?int
{
    if (!is_string($value) && !is_int($value)) {
        return null;
    }

    $value = (string) $value;
    if (!preg_match('/^[0-9]+$/', $value)) {
        return null;
    }

    $number = filter_var($value, FILTER_VALIDATE_INT);
    return $number === false ? null : $number;
}

function format_rupiah(int|float $value): string
{
    return 'Rp' . number_format($value, 0, ',', '.');
}
