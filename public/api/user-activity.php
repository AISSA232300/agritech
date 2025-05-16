<?php
/**
 * API endpoint for fetching user activity
 * Returns the most recent activities for the current user
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

// Get database connection
$pdo = getDbConnection();

// Get user activities from database (limit to 10 most recent)
$stmt = $pdo->prepare("SELECT * FROM activity_logs WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 10");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return activities
echo json_encode([
    'success' => true,
    'activities' => $activities
]);
