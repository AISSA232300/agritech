<?php
/**
 * History API for Agritech Bechar
 * Handles history operations
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
        // Get history records
        if (isset($_GET['id'])) {
            // Get specific history record
            $history = HistoryOperations::getHistoryById($_GET['id']);
            
            if ($history) {
                echo json_encode(['success' => true, 'history' => $history]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'History record not found']);
            }
        } else {
            // Get all history records or filter by user_id, action_type, start_date, or end_date
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            $actionType = isset($_GET['action_type']) ? $_GET['action_type'] : null;
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
            
            // If user is not admin or gestionaire, only show their own history
            if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire') {
                $userId = $_SESSION['user_id'];
            }
            
            $history = HistoryOperations::getAllHistory($userId, $actionType, $startDate, $endDate);
            echo json_encode(['success' => true, 'history' => $history]);
        }
        break;
        
    case 'POST':
        // Add history record
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['action_type']) || !isset($data['description'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            break;
        }
        
        // Set user_id if not provided
        if (!isset($data['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        
        // Add history record
        $historyId = HistoryOperations::addHistory([
            'user_id' => $data['user_id'],
            'task_id' => isset($data['task_id']) ? $data['task_id'] : null,
            'alert_id' => isset($data['alert_id']) ? $data['alert_id'] : null,
            'action_type' => $data['action_type'],
            'description' => $data['description'],
            'details' => isset($data['details']) ? $data['details'] : null
        ]);
        
        if ($historyId) {
            $history = HistoryOperations::getHistoryById($historyId);
            echo json_encode(['success' => true, 'history' => $history]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add history record']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
