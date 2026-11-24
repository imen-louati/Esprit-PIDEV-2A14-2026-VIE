<?php

namespace App\Core;

final class Security
{
    public static function boot(): void
    {
        $config = require __DIR__ . '/../../config.php';

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            if (self::isHttps()) {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        $lifetime = (int) ($config['session_lifetime'] ?? 1800);
        if (isset($_SESSION['last_activity']) && (time() - (int) $_SESSION['last_activity']) > $lifetime) {
            $hadUser = !empty($_SESSION['user_id']);
            $_SESSION = [];
            session_destroy();
            session_start();
            if ($hadUser) {
                Flash::set('warning', 'Votre session a expiré après 30 minutes d\'inactivité. Reconnectez-vous.');
            }
        }
        $_SESSION['last_activity'] = time();

        self::sendHeaders();
    }

    private static function isHttps(): bool
    {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    private static function sendHeaders(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data:; media-src 'self'; script-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self'");
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['csrf_token'];
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verifyCsrf(?string $token): bool
    {
        if ($token === null || $token === '' || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals((string) $_SESSION['csrf_token'], $token);
    }

    public static function homePage(string $role): string
    {
        $pages = [
            'admin' => 'dashboard',
            'medecin' => 'doctor',
            'patient' => 'patient',
            'proche' => 'proche',
        ];
        return $pages[$role] ?? 'login';
    }

    public static function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public static function restartSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['last_activity'] = time();
    }

    public static function clientIp(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    }
}
