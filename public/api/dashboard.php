<?php
/**
 * Dashboard API for Agritech Bechar
 * Provides data for the dashboard
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
        // Get dashboard data
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        
        // Get user's pivots
        if ($userRole === 'admin' || $userRole === 'gestionaire') {
            $pivots = PivotOperations::getAllPivots();
        } else {
            $pivots = PivotOperations::getAllPivots($userId);
        }
        
        // Get pending tasks
        if ($userRole === 'admin' || $userRole === 'gestionaire') {
            $pendingTasks = TaskOperations::getAllTasks(null, null, 'pending');
        } else {
            $pendingTasks = TaskOperations::getAllTasks($userId, null, 'pending');
        }
        
        // Get unresolved alerts
        $unresolvedAlerts = [];
        foreach ($pivots as $pivot) {
            $pivotAlerts = AlertOperations::getAllAlerts($pivot['id'], false);
            $unresolvedAlerts = array_merge($unresolvedAlerts, $pivotAlerts);
        }
        
        // Get completed tasks (for history)
        if ($userRole === 'admin' || $userRole === 'gestionaire') {
            $completedTasks = TaskOperations::getAllTasks(null, null, 'completed');
        } else {
            $completedTasks = TaskOperations::getAllTasks($userId, null, 'completed');
        }
        
        // Generate farming data for each pivot
        $farmingData = [];
        foreach ($pivots as $pivot) {
            $farmingData[$pivot['id']] = generateDashboardData($pivot['id']);
        }
        
        // Prepare dashboard data
        $dashboardData = [
            'user' => [
                'id' => $userId,
                'role' => $userRole
            ],
            'counts' => [
                'pivots' => count($pivots),
                'pending_tasks' => count($pendingTasks),
                'unresolved_alerts' => count($unresolvedAlerts),
                'completed_tasks' => count($completedTasks)
            ],
            'pivots' => $pivots,
            'pending_tasks' => array_slice($pendingTasks, 0, 5), // Limit to 5 tasks
            'unresolved_alerts' => array_slice($unresolvedAlerts, 0, 5), // Limit to 5 alerts
            'farming_data' => $farmingData
        ];
        
        echo json_encode(['success' => true, 'dashboard' => $dashboardData]);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
