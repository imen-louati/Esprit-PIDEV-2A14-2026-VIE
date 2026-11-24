<?php

namespace App\Config;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function getConnexion(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config.php';
            $d = $config['db'];
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $d['host'], $d['port'], $d['name']);

            try {
                self::$pdo = new PDO($dsn, $d['user'], $d['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                http_response_code(500);
                header('Content-Type: text/html; charset=utf-8');
                exit('<p style="font-family:sans-serif;padding:32px">Connexion à la base de données impossible. Vérifiez <code>config.php</code> et importez <code>database/database.sql</code> dans phpMyAdmin.</p>');
            }
        }

        return self::$pdo;
    }
}
