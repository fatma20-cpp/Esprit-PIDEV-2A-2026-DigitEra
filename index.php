<?php
session_start();

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require_once __DIR__ . '/controller/login.php';
        break;
    case 'register':
        require_once __DIR__ . '/controller/register.php';
        break;
    case 'dashboard':
        require_once __DIR__ . '/controller/index.php';
        break;
    case 'client_home':
        require_once __DIR__ . '/controller/client_home.php';
        break;
    case 'profile':
        require_once __DIR__ . '/controller/profile.php';
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
}