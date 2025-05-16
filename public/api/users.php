<?php
/**
 * Users API for Agritech Bechar
 * Handles user CRUD operations
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request for debugging
$requestLog = [
    'time' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'get' => $_GET,
    'post' => file_get_contents('php://input')
];
error_log('API Request: ' . json_encode($requestLog));

// Include the database operations
require_once '../../database/db_operations.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Cache-Control, Pragma, Expires');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For development, allow access without authentication
// In production, uncomment this block
/*
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
*/

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        // Get users
        if (isset($_GET['id'])) {
            // Get specific user
            $user = UserOperations::getUserById($_GET['id']);

            if ($user) {
                // Remove password from response
                unset($user['password']);
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } else {
            // Log the request
            error_log("GET request for all users received");

            try {
                // Get all users
                $users = UserOperations::getAllUsers();

                // Log the result
                error_log("Users retrieved: " . count($users));

                // If no users found, check if the table exists and has data
                if (empty($users)) {
                    $tableCheck = query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
                    error_log("Table check: " . json_encode($tableCheck));

                    if (!empty($tableCheck)) {
                        $countCheck = queryValue("SELECT COUNT(*) FROM users");
                        error_log("User count: " . $countCheck);
                    }
                }

                // Remove passwords from response
                foreach ($users as &$user) {
                    unset($user['password']);
                }

                // Return the response
                echo json_encode(['success' => true, 'users' => $users]);
            } catch (Exception $e) {
                error_log("Error in GET users: " . $e->getMessage());
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        }
        break;

    case 'POST':
        try {
            // Log the raw input
            error_log('POST raw input: ' . file_get_contents('php://input'));

            // Create user
            $data = json_decode(file_get_contents('php://input'), true);

            // Log the parsed data
            error_log('POST parsed data: ' . json_encode($data));

            // Validate required fields
            if (!isset($data['name']) || !isset($data['username']) || !isset($data['email']) || !isset($data['password']) || !isset($data['role_id'])) {
                $missingFields = [];
                if (!isset($data['name'])) $missingFields[] = 'name';
                if (!isset($data['username'])) $missingFields[] = 'username';
                if (!isset($data['email'])) $missingFields[] = 'email';
                if (!isset($data['password'])) $missingFields[] = 'password';
                if (!isset($data['role_id'])) $missingFields[] = 'role_id';

                error_log('Missing required fields: ' . implode(', ', $missingFields));
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missingFields)]);
                break;
            }

            // Check if username or email already exists
            $existingUser = UserOperations::getUserByUsername($data['username']);
            if ($existingUser) {
                error_log('Username already exists: ' . $data['username']);
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Username already exists']);
                break;
            }

            // Make email check case-insensitive
            $existingEmail = queryOne("SELECT * FROM users WHERE LOWER(email) = LOWER(?)", [$data['email']]);
            if ($existingEmail) {
                error_log('Email already exists: ' . $data['email']);
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                break;
            }

            // Prepare user data
            $userData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $data['role_id'],
                'is_active' => isset($data['is_active']) ? $data['is_active'] : 1,
                'status' => isset($data['status']) ? $data['status'] : 'Hors ligne'
            ];

            // Log the user data being created
            error_log('Creating user with data: ' . json_encode($userData));

            // Create user
            $userId = UserOperations::createUser($userData);

            if ($userId) {
                $user = UserOperations::getUserById($userId);
                if ($user) {
                    unset($user['password']);
                    error_log('User created successfully with ID: ' . $userId);
                    echo json_encode(['success' => true, 'user' => $user]);
                } else {
                    error_log('User created but could not retrieve: ' . $userId);
                    echo json_encode(['success' => true, 'message' => 'User created but could not retrieve details', 'user_id' => $userId]);
                }
            } else {
                error_log('Failed to create user');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to create user']);
            }
        } catch (Exception $e) {
            error_log('Exception in POST: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            // Log the raw input
            error_log('PUT raw input: ' . file_get_contents('php://input'));

            // Update user
            $data = json_decode(file_get_contents('php://input'), true);

            // Log the parsed data
            error_log('PUT parsed data: ' . json_encode($data));

            if (!isset($_GET['id'])) {
                error_log('PUT missing user ID');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'User ID is required']);
                break;
            }

            $userId = $_GET['id'];
            error_log('Updating user with ID: ' . $userId);

            $user = UserOperations::getUserById($userId);

            if (!$user) {
                error_log('User not found with ID: ' . $userId);
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found']);
                break;
            }

            // Check if this is a status update only
            if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($data['status'])) {
                error_log('Updating user status to: ' . $data['status']);
                $updated = UserOperations::updateUserStatus($userId, $data['status']);

                if ($updated) {
                    $updatedUser = UserOperations::getUserById($userId);
                    unset($updatedUser['password']);
                    error_log('User status updated successfully');
                    echo json_encode(['success' => true, 'user' => $updatedUser]);
                } else {
                    error_log('Failed to update user status');
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
                }
                break;
            }

            // Check if username or email already exists (if changing)
            if (isset($data['username']) && $data['username'] !== $user['username']) {
                $existingUser = UserOperations::getUserByUsername($data['username']);
                if ($existingUser) {
                    error_log('Username already exists: ' . $data['username']);
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Username already exists']);
                    break;
                }
            }

            if (isset($data['email']) && $data['email'] !== $user['email']) {
                $existingEmail = queryOne("SELECT * FROM users WHERE LOWER(email) = LOWER(?)", [$data['email']]);
                if ($existingEmail) {
                    error_log('Email already exists: ' . $data['email']);
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Email already exists']);
                    break;
                }
            }

            // Log the data being updated
            error_log('Updating user with data: ' . json_encode($data));

            // Update user
            $updated = UserOperations::updateUser($userId, $data);

            if ($updated) {
                $updatedUser = UserOperations::getUserById($userId);
                unset($updatedUser['password']);
                error_log('User updated successfully');
                echo json_encode(['success' => true, 'user' => $updatedUser]);
            } else {
                error_log('Failed to update user');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update user']);
            }
        } catch (Exception $e) {
            error_log('Exception in PUT: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            // Log the DELETE request
            error_log('DELETE request for user');

            if (!isset($_GET['id'])) {
                error_log('DELETE missing user ID');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'User ID is required']);
                break;
            }

            $userId = $_GET['id'];
            error_log('Deleting user with ID: ' . $userId);

            // Check if user exists
            $user = UserOperations::getUserById($userId);

            if (!$user) {
                error_log('User not found with ID: ' . $userId);
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found']);
                break;
            }

            // Prevent deleting the current user if logged in
            if (isset($_SESSION['user_id']) && $userId == $_SESSION['user_id']) {
                error_log('Attempted to delete current user');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cannot delete the current user']);
                break;
            }

            // Delete user
            $deleted = UserOperations::deleteUser($userId);

            if ($deleted) {
                error_log('User deleted successfully');
                echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                error_log('Failed to delete user');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (Exception $e) {
            error_log('Exception in DELETE: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
