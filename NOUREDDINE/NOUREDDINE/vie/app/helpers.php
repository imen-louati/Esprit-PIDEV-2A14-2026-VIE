<?php
function navActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name));
    $letters = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $letters .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $letters;
}
