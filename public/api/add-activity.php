<?php
/**
 * API endpoint for adding a new user activity
 * Records activities such as login, password change, etc.
 */

// Include the database connection
require_once __DIR__ . '/../database/db_connect.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Start session to get user info
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated'
    ]);
    exit;
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Get request data
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Check if required fields are provided
if (!isset($data['type']) || !isset($data['description'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Activity type and description are required'
    ]);
    exit;
}

$type = $data['type'];
$description = $data['description'];

// Get database connection
$pdo = getDbConnection();

// Add activity to database
$stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, entity_type, description, created_at) 
                      VALUES (:user_id, :action, 'user', :description, :created_at)");
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':action', $type);
$stmt->bindParam(':description', $description);
$now = date('Y-m-d H:i:s');
$stmt->bindParam(':created_at', $now);
$result = $stmt->execute();

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Activity added successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add activity'
    ]);
}
