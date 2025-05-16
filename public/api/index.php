<?php
/**
 * API Router for Agritech Bechar
 * Routes API requests to the appropriate endpoint
 */

// Set headers for JSON response
header('Content-Type: application/json');

// Get the request path
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/api';
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove base path if present
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Remove leading slash if present
if (strpos($path, '/') === 0) {
    $path = substr($path, 1);
}

// Route to the appropriate endpoint
switch ($path) {
    case 'auth':
    case 'auth/':
        require_once 'auth.php';
        break;
        
    case 'users':
    case 'users/':
        require_once 'users.php';
        break;
        
    case 'pivots':
    case 'pivots/':
        require_once 'pivots.php';
        break;
        
    case 'tasks':
    case 'tasks/':
        require_once 'tasks.php';
        break;
        
    case 'alerts':
    case 'alerts/':
        require_once 'alerts.php';
        break;
        
    case 'history':
    case 'history/':
        require_once 'history.php';
        break;
        
    case 'preferences':
    case 'preferences/':
        require_once 'preferences.php';
        break;
        
    case 'weather':
    case 'weather/':
        require_once 'weather.php';
        break;
        
    case 'dashboard':
    case 'dashboard/':
        require_once 'dashboard.php';
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'API endpoint not found']);
        break;
}
