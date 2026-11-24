<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

use App\Controllers\NotFoundController;

$routes = require __DIR__ . '/routes.php';

$page = $_GET['page'] ?? 'home';
$page = is_string($page) ? $page : 'home';

if (!array_key_exists($page, $routes)) {
    $controllerClass = NotFoundController::class;
    $method = 'index';
} else {
    $controllerClass = $routes[$page][0];
    $method = $routes[$page][1];
}

$controller = new $controllerClass();
$controller->$method();
