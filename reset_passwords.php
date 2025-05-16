<?php
/**
 * Password Reset Script
 * 
 * This script resets all user passwords in the database to "123"
 * with proper password hashing.
 */

// Include the database connection helper
require_once __DIR__ . '/database/db_connect.php';

echo "Starting password reset process...\n";

try {
    // Get database connection
    $pdo = getDbConnection();
    
    // Get all users
    $users = query("SELECT id, username FROM users");
    
    if (empty($users)) {
        echo "No users found in the database.\n";
        exit;
    }
    
    echo "Found " . count($users) . " users in the database.\n";
    
    // Hash the new password
    $newPassword = "123";
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update each user's password
    $updatedCount = 0;
    foreach ($users as $user) {
        $result = update('users', 
            ['password' => $hashedPassword], 
            'id = :id', 
            ['id' => $user['id']]
        );
        
        if ($result) {
            $updatedCount++;
            echo "Updated password for user: " . $user['username'] . "\n";
        } else {
            echo "Failed to update password for user: " . $user['username'] . "\n";
        }
    }
    
    echo "\nPassword reset completed.\n";
    echo "Successfully updated passwords for $updatedCount out of " . count($users) . " users.\n";
    echo "All passwords have been reset to \"$newPassword\".\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
