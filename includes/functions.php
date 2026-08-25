<?php

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function app_base_url(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $firstSegment = explode('/', trim($script, '/'))[0] ?? '';

    if ($firstSegment !== '' && file_exists(($_SERVER['DOCUMENT_ROOT'] ?? '') . '/' . $firstSegment . '/index.php')) {
        return '/' . $firstSegment;
    }

    return '';
}

function app_url(string $path = ''): string
{
    return rtrim(app_base_url(), '/') . '/' . ltrim($path, '/');
}

function current_user(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function format_date(?string $date): string
{
    if (!$date) {
        return '-';
    }

    return date('d/m/Y', strtotime($date));
}

function course_situation(?string $status, ?string $validUntil): string
{
    if ($status !== 'concluido') {
        return 'pendente';
    }

    if ($validUntil && strtotime($validUntil) < strtotime(date('Y-m-d'))) {
        return 'vencido';
    }

    return 'valido';
}
