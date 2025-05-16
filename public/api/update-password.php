<?php
/**
 * API endpoint for updating user password
 * Only allows changing the password, not other user information
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
if (!isset($data['current_password']) || !isset($data['new_password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Current password and new password are required'
    ]);
    exit;
}

$currentPassword = $data['current_password'];
$newPassword = $data['new_password'];

// Get database connection
$pdo = getDbConnection();

// Get user from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit;
}

// Verify current password
if (!password_verify($currentPassword, $user['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Current password is incorrect'
    ]);
    exit;
}

// Hash new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password in database
$stmt = $pdo->prepare("UPDATE users SET password = :password, updated_at = :updated_at WHERE id = :id");
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':id', $userId);
$now = date('Y-m-d H:i:s');
$stmt->bindParam(':updated_at', $now);
$result = $stmt->execute();

if ($result) {
    // Log activity
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, entity_type, description, created_at) 
                          VALUES (:user_id, 'password_change', 'user', 'Password changed', :created_at)");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':created_at', $now);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => 'Password updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update password'
    ]);
}
