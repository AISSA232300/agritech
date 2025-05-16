<?php
/**
 * Authentication API for Agritech Bechar
 * Handles user login, logout, and session management
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
error_log('Auth API Request: ' . json_encode($requestLog));

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

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'POST':
        // Login request
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action']) && $data['action'] === 'login') {
            handleLogin($data);
        } elseif (isset($data['action']) && $data['action'] === 'logout') {
            handleLogout();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    case 'GET':
        // Check authentication status
        if (isset($_GET['action']) && $_GET['action'] === 'check') {
            checkAuth();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

/**
 * Handle user login
 *
 * @param array $data Login data
 */
function handleLogin($data) {
    try {
        error_log('Processing login request: ' . json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR));

        if (!isset($data['username']) || !isset($data['password'])) {
            error_log('Login failed: Missing username or password');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nom d\'utilisateur et mot de passe sont requis']);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];
        $remember = isset($data['remember']) ? $data['remember'] : false;

        error_log("Attempting login for username: $username");

        // Authenticate user
        $user = UserOperations::getUserByUsername($username);

        if (!$user) {
            error_log("Login failed: Username not found: $username");
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Nom d\'utilisateur introuvable.']);
            return;
        }

        error_log("User found, verifying password");

        // Check if the password is already hashed
        $isHashed = password_get_info($user['password'])['algo'] !== 0;

        // Verify password
        if ($isHashed) {
            // Password is properly hashed, use password_verify
            $passwordValid = password_verify($password, $user['password']);
            error_log("Password is hashed, verification result: " . ($passwordValid ? "valid" : "invalid"));
        } else {
            // For backward compatibility - direct comparison (not secure)
            // This should only happen for old accounts or during development
            $passwordValid = $user['password'] === $password;
            error_log("Password is not hashed, direct comparison result: " . ($passwordValid ? "valid" : "invalid"));

            // If password is valid but not hashed, update it with a proper hash
            if ($passwordValid) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                UserOperations::updateUser($user['id'], ['password' => $hashedPassword]);
                error_log("Updated user password with secure hash");
            }
        }

        if (!$passwordValid) {
            error_log("Login failed: Incorrect password for user: $username");
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
            return;
        }

        error_log("Password verified, updating user status");

        // Update user status to online
        UserOperations::updateUserStatus($user['id'], 'En ligne');

        // Create a safe user object without the password
        $safeUser = [
            'id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role_name'],
            'status' => 'En ligne'
        ];

        // Store user in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role_name'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['last_login_time'] = date('Y-m-d H:i:s');

        // Set remember cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expiry = time() + (30 * 24 * 60 * 60); // 30 days

            // Store token in database
            UserOperations::updateUser($user['id'], ['remember_token' => $token]);

            // Set cookie
            setcookie('remember_token', $token, $expiry, '/', '', false, true);
            error_log("Remember token set for user: $username");
        }

        error_log("Login successful for user: $username");

        // Return success response
        echo json_encode(['success' => true, 'user' => $safeUser]);
    } catch (Exception $e) {
        error_log("Exception during login: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de serveur lors de la connexion.']);
    }
}

/**
 * Handle user logout
 */
function handleLogout() {
    try {
        error_log('Processing logout request');

        // Check if user is logged in
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            error_log("Logging out user with ID: $userId");

            // Update user status to offline
            UserOperations::updateUserStatus($userId, 'Hors ligne');
            error_log("User status updated to offline");

            // Clear remember token
            UserOperations::updateUser($userId, ['remember_token' => null]);
            error_log("Remember token cleared in database");
        } else {
            error_log("No user ID found in session");
        }

        // Clear remember cookie
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        error_log("Remember token cookie cleared");

        // Clear session
        session_unset();
        session_destroy();
        error_log("Session destroyed");

        // Return success response
        echo json_encode(['success' => true, 'message' => 'Déconnexion réussie']);
    } catch (Exception $e) {
        error_log("Exception during logout: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de serveur lors de la déconnexion.']);
    }
}

/**
 * Check authentication status
 */
function checkAuth() {
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
        $userId = $_SESSION['user_id'];
        $user = UserOperations::getUserById($userId);

        if ($user) {
            // Create a safe user object without the password
            $safeUser = [
                'id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role_name'],
                'status' => $user['status']
            ];

            echo json_encode(['success' => true, 'authenticated' => true, 'user' => $safeUser]);
        } else {
            // User not found in database
            session_unset();
            session_destroy();
            echo json_encode(['success' => true, 'authenticated' => false]);
        }
    } else {
        // Check for remember token
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];

            // Find user with this token
            $user = queryOne("SELECT * FROM users WHERE remember_token = ?", [$token]);

            if ($user) {
                // Log user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role_name'];
                $_SESSION['is_logged_in'] = true;
                $_SESSION['last_login_time'] = date('Y-m-d H:i:s');

                // Create a safe user object without the password
                $safeUser = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role_name'],
                    'status' => $user['is_active'] ? 'online' : 'offline'
                ];

                echo json_encode(['success' => true, 'authenticated' => true, 'user' => $safeUser]);
                return;
            }
        }

        echo json_encode(['success' => true, 'authenticated' => false]);
    }
}
