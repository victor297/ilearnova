<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/AuthController.php';
require_once __DIR__ . '/../src/PasswordResetController.php';

$pdo = require __DIR__ . '/../config/db.php';
$auth = new AuthController($pdo);
$resetCtrl = new PasswordResetController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$data = json_decode(file_get_contents('php://input'), true);

header('Access-Control-Allow-Origin: http://localhost:3000'); //frontendURL!
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//routes
switch ($uri) {
    case '/api/signup':
        if ($method === 'POST') $auth->signup($data);
        break;
    case '/api/login':
        if ($method === 'POST') $auth->login($data);
        break;
    case '/api/logout':
        if ($method === 'POST') $auth->logout();
        break;
    case '/api/me':
        if ($method === 'GET') $auth->me();
        break;
    case '/api/password-reset-request':
        if ($method === 'POST') $resetCtrl->requestReset($data);
        break;
    case '/api/reset-password':
        if ($method === 'POST') $resetCtrl->resetPassword($data);
        break;
    default:
        json_response(['error' => 'Not found'], 404);
        break;
}
