<?php

namespace App\Core;

final class Mailer
{
    public static function send(string $to, string $subject, string $body): bool
    {
        $config = require __DIR__ . '/../../config.php';
        $from = (string) $config['mail_from'];

        if (($config['mail_mode'] ?? 'log') === 'mail') {
            $headers = [
                'From: VIE <' . $from . '>',
                'Content-Type: text/plain; charset=UTF-8',
                'X-Mailer: PHP/' . PHP_VERSION,
            ];
            return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
        }

        $entry = '[' . date('Y-m-d H:i:s') . '] To: ' . $to . "\nSubject: " . $subject . "\n\n" . $body . "\n" . str_repeat('-', 60) . "\n";
        return file_put_contents(__DIR__ . '/../../storage/mails.log', $entry, FILE_APPEND | LOCK_EX) !== false;
    }
}
