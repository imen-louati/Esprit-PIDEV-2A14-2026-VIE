<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function navActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}

function roleLabel(string $role): string
{
    $labels = [
        'admin' => 'Administrateur',
        'medecin' => 'Médecin',
        'patient' => 'Patient',
        'proche' => 'Proche autorisé',
    ];
    return $labels[$role] ?? 'Invité';
}

function statusLabel(string $status): string
{
    $labels = [
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'blocked' => 'Bloqué',
    ];
    return $labels[$status] ?? $status;
}

function statusBadge(string $status): string
{
    $classes = [
        'active' => 'b-green',
        'inactive' => 'b-amber',
        'blocked' => 'b-red',
    ];
    return $classes[$status] ?? 'b-gray';
}

function roleBadge(string $role): string
{
    $classes = [
        'admin' => 'b-gray',
        'medecin' => 'b-violet',
        'patient' => 'b-pink',
        'proche' => 'b-blue',
    ];
    return $classes[$role] ?? 'b-gray';
}

function avatarClass(string $role): string
{
    $classes = [
        'admin' => 'av-navy',
        'medecin' => 'av-violet',
        'patient' => 'av-pink',
        'proche' => 'av-blue',
    ];
    return $classes[$role] ?? 'av-navy';
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $letters = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $letters .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $letters !== '' ? $letters : '?';
}

function formatDate(?string $value, bool $withTime = false): string
{
    if ($value === null || $value === '') {
        return '—';
    }
    try {
        $date = new DateTimeImmutable($value);
    } catch (Exception $e) {
        return '—';
    }
    $months = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $text = $date->format('d') . ' ' . $months[(int) $date->format('n') - 1] . ' ' . $date->format('Y');
    return $withTime ? $text . ', ' . $date->format('H:i') : $text;
}

function fieldError(array $errors, string $key): string
{
    if (!isset($errors[$key])) {
        return '';
    }
    return '<p class="field-error" role="alert">' . e($errors[$key]) . '</p>';
}

function hasError(array $errors, string $key): string
{
    return isset($errors[$key]) ? ' has-error' : '';
}
