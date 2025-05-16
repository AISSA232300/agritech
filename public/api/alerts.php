<?php
/**
 * Alerts API for Agritech Bechar
 * Handles alert CRUD operations
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
        // Get alerts
        if (isset($_GET['id'])) {
            // Get specific alert
            $alert = AlertOperations::getAlertById($_GET['id']);
            
            if ($alert) {
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Alert not found']);
            }
        } else {
            // Get all alerts or filter by pivot_id or is_resolved
            $pivotId = isset($_GET['pivot_id']) ? $_GET['pivot_id'] : null;
            $isResolved = isset($_GET['is_resolved']) ? (bool)$_GET['is_resolved'] : null;
            
            // If user is not admin or gestionaire, only show alerts for their pivots
            if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire') {
                // Get user's pivots
                $userPivots = PivotOperations::getAllPivots($_SESSION['user_id']);
                $userPivotIds = array_column($userPivots, 'id');
                
                if ($pivotId && !in_array($pivotId, $userPivotIds)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Permission denied']);
                    break;
                }
                
                if (!$pivotId) {
                    // Get alerts for all user's pivots
                    $alerts = [];
                    foreach ($userPivotIds as $id) {
                        $pivotAlerts = AlertOperations::getAllAlerts($id, $isResolved);
                        $alerts = array_merge($alerts, $pivotAlerts);
                    }
                    echo json_encode(['success' => true, 'alerts' => $alerts]);
                    break;
                }
            }
            
            $alerts = AlertOperations::getAllAlerts($pivotId, $isResolved);
            echo json_encode(['success' => true, 'alerts' => $alerts]);
        }
        break;
        
    case 'POST':
        // Create alert
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['pivot_id']) || !isset($data['type']) || !isset($data['title']) || !isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            break;
        }
        
        // Create alert
        $alertId = AlertOperations::createAlert([
            'pivot_id' => $data['pivot_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'details' => isset($data['details']) ? $data['details'] : null,
            'task_id' => isset($data['task_id']) ? $data['task_id'] : null,
            'is_resolved' => isset($data['is_resolved']) ? $data['is_resolved'] : 0
        ]);
        
        if ($alertId) {
            $alert = AlertOperations::getAlertById($alertId);
            echo json_encode(['success' => true, 'alert' => $alert]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create alert']);
        }
        break;
        
    case 'PUT':
        // Update alert
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Alert ID is required']);
            break;
        }
        
        $alertId = $_GET['id'];
        $alert = AlertOperations::getAlertById($alertId);
        
        if (!$alert) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Alert not found']);
            break;
        }
        
        // Check if this is an alert resolution request
        if (isset($data['action']) && $data['action'] === 'resolve') {
            if (!isset($data['action_taken'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Action taken is required']);
                break;
            }
            
            $resolved = AlertOperations::resolveAlert($alertId, $_SESSION['user_id'], $data['action_taken']);
            
            if ($resolved) {
                $updatedAlert = AlertOperations::getAlertById($alertId);
                echo json_encode(['success' => true, 'alert' => $updatedAlert]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to resolve alert']);
            }
            break;
        }
        
        // Regular update
        $updated = AlertOperations::updateAlert($alertId, $data);
        
        if ($updated) {
            $updatedAlert = AlertOperations::getAlertById($alertId);
            echo json_encode(['success' => true, 'alert' => $updatedAlert]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update alert']);
        }
        break;
        
    case 'DELETE':
        // Delete alert
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Alert ID is required']);
            break;
        }
        
        $alertId = $_GET['id'];
        
        // Check if alert exists
        $alert = AlertOperations::getAlertById($alertId);
        
        if (!$alert) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Alert not found']);
            break;
        }
        
        // Check if user has permission to delete this alert
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Delete alert
        $deleted = AlertOperations::deleteAlert($alertId);
        
        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete alert']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
