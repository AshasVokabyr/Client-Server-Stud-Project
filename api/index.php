<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/controllers/UserController.php';

$routes = require __DIR__ . '/routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['RUQUEST_URI']);
$uri = preg_replace('#^/api/#', '', $uri);
$uri = $uri === '' ? '/' : $uri;

$routeFound = null;
$params = [];

foreach ($routes as $routePattern => $handler) {
    list($routeMethod, $routeUri) = explode(' ', $routePattern, 2);
    if ($routeMethod !== $method) continue;
    $pattern = '#^' . preg_replace('/\{[a-z]+\}/', '([^/]+)', $routeUri) . '$#';
    if (preg_match($pattern, $uri, $matches)) {
        $routeFound = $handler;
        array_shift($matches);
        $params = $matches;
        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
    exit;
}

$controllerName = $routeFound[0];
$action = $routeFound[1];
$controller = new UserController();

call_user_func_array([$controller, $action], $params);