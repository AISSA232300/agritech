<?php
/**
 * Tasks API for Agritech Bechar
 * Handles task CRUD operations
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
        // Get tasks
        if (isset($_GET['id'])) {
            // Get specific task
            $task = TaskOperations::getTaskById($_GET['id']);
            
            if ($task) {
                echo json_encode(['success' => true, 'task' => $task]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Task not found']);
            }
        } else {
            // Get all tasks or filter by user_id, pivot_id, or status
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            $pivotId = isset($_GET['pivot_id']) ? $_GET['pivot_id'] : null;
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            
            // If user is not admin or gestionaire, only show their own tasks
            if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire') {
                $userId = $_SESSION['user_id'];
            }
            
            $tasks = TaskOperations::getAllTasks($userId, $pivotId, $status);
            echo json_encode(['success' => true, 'tasks' => $tasks]);
        }
        break;
        
    case 'POST':
        // Create task
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['pivot_id']) || !isset($data['title'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            break;
        }
        
        // Set user_id if not provided
        if (!isset($data['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        
        // Create task
        $taskId = TaskOperations::createTask([
            'user_id' => $data['user_id'],
            'pivot_id' => $data['pivot_id'],
            'title' => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'priority' => isset($data['priority']) ? $data['priority'] : 'medium',
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'due_date' => isset($data['due_date']) ? $data['due_date'] : null
        ]);
        
        if ($taskId) {
            $task = TaskOperations::getTaskById($taskId);
            echo json_encode(['success' => true, 'task' => $task]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create task']);
        }
        break;
        
    case 'PUT':
        // Update task
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Task ID is required']);
            break;
        }
        
        $taskId = $_GET['id'];
        $task = TaskOperations::getTaskById($taskId);
        
        if (!$task) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Task not found']);
            break;
        }
        
        // Check if this is a task completion request
        if (isset($data['action']) && $data['action'] === 'complete') {
            $completed = TaskOperations::completeTask($taskId, $_SESSION['user_id']);
            
            if ($completed) {
                $updatedTask = TaskOperations::getTaskById($taskId);
                echo json_encode(['success' => true, 'task' => $updatedTask]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to complete task']);
            }
            break;
        }
        
        // Regular update
        $updated = TaskOperations::updateTask($taskId, $data);
        
        if ($updated) {
            $updatedTask = TaskOperations::getTaskById($taskId);
            echo json_encode(['success' => true, 'task' => $updatedTask]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update task']);
        }
        break;
        
    case 'DELETE':
        // Delete task
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Task ID is required']);
            break;
        }
        
        $taskId = $_GET['id'];
        
        // Check if task exists
        $task = TaskOperations::getTaskById($taskId);
        
        if (!$task) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Task not found']);
            break;
        }
        
        // Check if user has permission to delete this task
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'gestionaire' && $task['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            break;
        }
        
        // Delete task
        $deleted = TaskOperations::deleteTask($taskId);
        
        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete task']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
