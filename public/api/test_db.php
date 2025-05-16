<?php
/**
 * Database Test Script
 * 
 * This script tests the database connection and user operations.
 * It can be accessed directly to verify that the database is working properly.
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database operations
require_once '../../database/db_operations.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Test database connection
try {
    $pdo = getDbConnection();
    $dbStatus = "Connected to database successfully";
    
    // Check if tables exist
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    $tableStatus = "Tables found: " . implode(", ", $tables);
    
    // Test user operations
    $users = UserOperations::getAllUsers();
    $userCount = count($users);
    
    // If no users, create a test user
    if ($userCount === 0) {
        $testUser = [
            'name' => 'Test User',
            'username' => 'testuser_' . time(),
            'email' => 'test_' . time() . '@example.com',
            'password' => 'password123',
            'role_id' => 2, // gestionnaire
            'is_active' => 1,
            'status' => 'Hors ligne'
        ];
        
        $userId = UserOperations::createUser($testUser);
        
        if ($userId) {
            $userStatus = "Created test user with ID: $userId";
            $users = UserOperations::getAllUsers();
            $userCount = count($users);
        } else {
            $userStatus = "Failed to create test user";
        }
    } else {
        $userStatus = "Found $userCount existing users";
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'database' => [
            'status' => $dbStatus,
            'tables' => $tableStatus
        ],
        'users' => [
            'status' => $userStatus,
            'count' => $userCount,
            'data' => $users
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
