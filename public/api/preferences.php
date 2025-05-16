<?php
/**
 * User Preferences API for Agritech Bechar
 * Handles user preferences operations
 */

// Include the database operations
require_once '../../database/db_operations.php';

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
        // Get user preferences
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
        
        // If user is not admin and trying to access another user's preferences
        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        $preferences = UserPreferencesOperations::getUserPreferences($userId);
        
        if ($preferences) {
            echo json_encode(['success' => true, 'preferences' => $preferences]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User preferences not found']);
        }
        break;
        
    case 'PUT':
        // Update user preferences
        $data = json_decode(file_get_contents('php://input'), true);
        
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
        
        // If user is not admin and trying to update another user's preferences
        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Update preferences
        $updated = UserPreferencesOperations::updateUserPreferences($userId, $data);
        
        if ($updated) {
            $preferences = UserPreferencesOperations::getUserPreferences($userId);
            echo json_encode(['success' => true, 'preferences' => $preferences]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update user preferences']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
