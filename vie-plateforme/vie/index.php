<?php
session_start();

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/app/' . $relative . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/app/helpers.php';

$routes = require __DIR__ . '/routes.php';

$page = $_GET['page'] ?? 'dashboard';
$page = is_string($page) ? $page : 'dashboard';

if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    $controllerClass = \App\Controllers\NotFoundController::class;
    $method = 'index';
} else {
    $controllerClass = $routes[$page][0];
    $method = $routes[$page][1] ?? 'index';
}

$controller = new $controllerClass();
$controller->$method();
