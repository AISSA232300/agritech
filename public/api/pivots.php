<?php
/**
 * Pivots API for Agritech Bechar
 * Handles pivot CRUD operations
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
        // Get pivots
        if (isset($_GET['id'])) {
            // Get specific pivot
            $pivot = PivotOperations::getPivotById($_GET['id']);
            
            if ($pivot) {
                echo json_encode(['success' => true, 'pivot' => $pivot]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pivot not found']);
            }
        } else {
            // Get all pivots or filter by user_id
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            
            // If user is not admin, only show their own pivots
            if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire') {
                $userId = $_SESSION['user_id'];
            }
            
            $pivots = PivotOperations::getAllPivots($userId);
            echo json_encode(['success' => true, 'pivots' => $pivots]);
        }
        break;
        
    case 'POST':
        // Create pivot
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['surface_area']) || !isset($data['crop_type'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            break;
        }
        
        // Set user_id if not provided
        if (!isset($data['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        
        // Create pivot
        $pivotId = PivotOperations::createPivot([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'surface_area' => $data['surface_area'],
            'crop_type' => $data['crop_type'],
            'location' => isset($data['location']) ? $data['location'] : null,
            'notes' => isset($data['notes']) ? $data['notes'] : null,
            'status' => isset($data['status']) ? $data['status'] : 'active'
        ]);
        
        if ($pivotId) {
            $pivot = PivotOperations::getPivotById($pivotId);
            echo json_encode(['success' => true, 'pivot' => $pivot]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create pivot']);
        }
        break;
        
    case 'PUT':
        // Update pivot
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Pivot ID is required']);
            break;
        }
        
        $pivotId = $_GET['id'];
        $pivot = PivotOperations::getPivotById($pivotId);
        
        if (!$pivot) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Pivot not found']);
            break;
        }
        
        // Check if user has permission to update this pivot
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire' && $pivot['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Update pivot
        $updated = PivotOperations::updatePivot($pivotId, $data);
        
        if ($updated) {
            $updatedPivot = PivotOperations::getPivotById($pivotId);
            echo json_encode(['success' => true, 'pivot' => $updatedPivot]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update pivot']);
        }
        break;
        
    case 'DELETE':
        // Delete pivot
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Pivot ID is required']);
            break;
        }
        
        $pivotId = $_GET['id'];
        
        // Check if pivot exists
        $pivot = PivotOperations::getPivotById($pivotId);
        
        if (!$pivot) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Pivot not found']);
            break;
        }
        
        // Check if user has permission to delete this pivot
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire' && $pivot['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Delete pivot
        $deleted = PivotOperations::deletePivot($pivotId);
        
        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete pivot']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
