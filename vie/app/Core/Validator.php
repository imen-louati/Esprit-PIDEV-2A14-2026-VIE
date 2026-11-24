<?php

namespace App\Core;

final class Validator
{
    public const ROLES = ['patient', 'proche', 'medecin', 'admin'];
    public const STATUSES = ['active', 'inactive', 'blocked'];
    public const SPECIALITIES = [
        'Oncologie médicale',
        'Radiothérapie',
        'Chirurgie oncologique',
        'Hématologie',
        'Pneumologie',
        'Gynécologie',
        'Urologie',
        'Gastro-entérologie',
        'Dermatologie',
        'Psycho-oncologie',
        'Médecine générale',
    ];
    public const RELATIONSHIPS = ['Conjoint(e)', 'Parent', 'Enfant', 'Frère / Sœur', 'Ami(e)', 'Autre'];
    public const CANCER_TYPES = ['Sein', 'Poumon', 'Leucémie', 'Colorectal', 'Prostate', 'Peau', 'Autre'];
    public const STAGES = ['Stade 0', 'Stade I', 'Stade II', 'Stade III', 'Stade IV', 'Non précisé'];

    public static function name(string $value, string $label): ?string
    {
        $length = mb_strlen($value);
        if ($length < 2 || $length > 100) {
            return $label . ' : entre 2 et 100 caractères.';
        }
        if (!preg_match("/^[\p{L}][\p{L}\p{M}' .\-]*$/u", $value)) {
            return $label . ' : lettres, espaces, apostrophes, points et tirets uniquement.';
        }
        return null;
    }

    public static function email(string $value): ?string
    {
        if ($value === '') {
            return 'L\'adresse e-mail est obligatoire.';
        }
        if (mb_strlen($value) > 255 || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'Adresse e-mail invalide.';
        }
        return null;
    }

    public static function phone(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        if (!preg_match('/^\+?[0-9][0-9 ]{6,17}[0-9]$/', $value)) {
            return 'Numéro de téléphone invalide (8 à 15 chiffres, indicatif + autorisé).';
        }
        return null;
    }

    public static function password(string $value): ?string
    {
        if (strlen($value) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if (strlen($value) > 72) {
            return 'Le mot de passe ne doit pas dépasser 72 caractères.';
        }
        if (!preg_match('/[a-z]/', $value) || !preg_match('/[A-Z]/', $value) || !preg_match('/[0-9]/', $value)) {
            return 'Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.';
        }
        return null;
    }

    public static function date(string $value): ?string
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            return 'Date invalide.';
        }
        return null;
    }

    public static function birthDate(string $value): ?string
    {
        if ($value === '') {
            return 'La date de naissance est obligatoire.';
        }
        if (self::date($value) !== null) {
            return 'Date de naissance invalide.';
        }
        if ($value > date('Y-m-d') || $value < '1900-01-01') {
            return 'La date de naissance doit être comprise entre 1900 et aujourd\'hui.';
        }
        return null;
    }

    public static function inList(string $value, array $list, string $message): ?string
    {
        return in_array($value, $list, true) ? null : $message;
    }

    public static function progress(string $value): ?string
    {
        return mb_strlen($value) > 1000 ? 'L\'état d\'avancement ne doit pas dépasser 1000 caractères.' : null;
    }

    public static function licenseNumber(string $value): ?string
    {
        return preg_match('/^[A-Za-z0-9\-\/]{4,30}$/', $value) ? null : 'N° de licence : 4 à 30 caractères (lettres, chiffres, - et /).';
    }

    public static function city(string $value): ?string
    {
        $length = mb_strlen($value);
        if ($length < 2 || $length > 100 || !preg_match("/^[\p{L}][\p{L}\p{M}' .\-]*$/u", $value)) {
            return 'Ville invalide (2 à 100 caractères, lettres uniquement).';
        }
        return null;
    }

    public static function shareCode(string $value): ?string
    {
        return preg_match('/^CC-[A-Z0-9]{4}-[0-9]{2}$/', $value) ? null : 'Format attendu : CC-XXXX-00.';
    }
}
