<?php
/**
 * Weather Settings API for Agritech Bechar
 * Handles weather settings operations
 */

// Include the database operations
require_once '../../database/db_operations.php';
require_once '../../generate_farming_data.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'data') {
            // Get weather data (simulated)
            $pivotId = isset($_GET['pivot_id']) ? $_GET['pivot_id'] : null;
            $data = generateFarmingData();
            echo json_encode(['success' => true, 'weather_data' => $data]);
            break;
        }
        
        // Get weather settings
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
        
        // If user is not admin and trying to access another user's settings
        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        $settings = WeatherSettingsOperations::getWeatherSettings($userId);
        
        if ($settings) {
            echo json_encode(['success' => true, 'settings' => $settings]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Weather settings not found']);
        }
        break;
        
    case 'PUT':
        // Update weather settings
        $data = json_decode(file_get_contents('php://input'), true);
        
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
        
        // If user is not admin and trying to update another user's settings
        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Validate required fields
        if (!isset($data['api_provider']) || !isset($data['api_key'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'API provider and API key are required']);
            break;
        }
        
        // Update settings
        $updated = WeatherSettingsOperations::updateWeatherSettings($userId, [
            'api_provider' => $data['api_provider'],
            'api_key' => $data['api_key'],
            'location' => isset($data['location']) ? $data['location'] : 'Béchar, Algeria',
            'is_active' => isset($data['is_active']) ? $data['is_active'] : 1
        ]);
        
        if ($updated) {
            $settings = WeatherSettingsOperations::getWeatherSettings($userId);
            echo json_encode(['success' => true, 'settings' => $settings]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update weather settings']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
