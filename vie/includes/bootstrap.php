<?php
declare(strict_types=1);

date_default_timezone_set('Africa/Tunis');
mb_internal_encoding('UTF-8');

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/../app/' . $relative . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/../app/helpers.php';

\App\Core\Security::boot();
