<?php

/**
 * Database Operations Example
 *
 * This file demonstrates how to use the database operations classes
 * to interact with the database.
 */

// Include the database operations
require_once __DIR__ . '/database/db_operations.php';

// Example: User Operations
echo "=== User Operations ===\n";

// Get all users
$users = UserOperations::getAllUsers();
echo "All users:\n";
foreach ($users as $user) {
    echo "- {$user['name']} ({$user['role_name']})\n";
}
echo "\n";

// Create a new user (gestionaire)
$gestionaireRoleId = queryValue("SELECT id FROM roles WHERE name = ?", ['gestionaire']);
$timestamp = time(); // Add timestamp to make email unique
$newUserId = UserOperations::createUser([
    'role_id' => $gestionaireRoleId,
    'name' => 'Gestionaire Example',
    'username' => "gestionaire_example_$timestamp",
    'email' => "gestionaire_example_$timestamp@agritech-bechar.com",
    'password' => 'password123',
    'is_active' => 1
]);
echo "Created new user with ID: $newUserId\n\n";

// Get user by ID
$user = UserOperations::getUserById($newUserId);
echo "User details:\n";
echo "- Name: {$user['name']}\n";
echo "- Username: {$user['username']}\n";
echo "- Role: {$user['role_name']}\n";
echo "\n";

// Update user
$updated = UserOperations::updateUser($newUserId, [
    'name' => 'Gestionaire Example Updated'
]);
echo "Updated user: $updated row(s) affected\n";
$user = UserOperations::getUserById($newUserId);
echo "Updated name: {$user['name']}\n\n";

// Example: Pivot Operations
echo "=== Pivot Operations ===\n";

// Create a new pivot
$pivotId = PivotOperations::createPivot([
    'user_id' => $newUserId,
    'name' => 'Pivot Example',
    'surface_area' => 150.5,
    'crop_type' => 'Blé',
    'location' => 'Béchar, Zone Sud',
    'notes' => 'Pivot d\'exemple',
    'status' => 'active'
]);
echo "Created new pivot with ID: $pivotId\n\n";

// Get pivot by ID
$pivot = PivotOperations::getPivotById($pivotId);
echo "Pivot details:\n";
echo "- Name: {$pivot['name']}\n";
echo "- Surface Area: {$pivot['surface_area']}\n";
echo "- Crop Type: {$pivot['crop_type']}\n";
echo "- Owner: {$pivot['user_name']}\n";
echo "\n";

// Update pivot
$updated = PivotOperations::updatePivot($pivotId, [
    'name' => 'Pivot Example Updated',
    'surface_area' => 200.0
]);
echo "Updated pivot: $updated row(s) affected\n";
$pivot = PivotOperations::getPivotById($pivotId);
echo "Updated name: {$pivot['name']}\n";
echo "Updated surface area: {$pivot['surface_area']}\n\n";

// Example: Task Operations
echo "=== Task Operations ===\n";

// Create a new task
$taskId = TaskOperations::createTask([
    'user_id' => $newUserId,
    'pivot_id' => $pivotId,
    'title' => 'Task Example',
    'description' => 'This is an example task',
    'priority' => 'high',
    'status' => 'pending',
    'due_date' => date('Y-m-d H:i:s', strtotime('+2 days'))
]);
echo "Created new task with ID: $taskId\n\n";

// Get task by ID
$task = TaskOperations::getTaskById($taskId);
echo "Task details:\n";
echo "- Title: {$task['title']}\n";
echo "- Priority: {$task['priority']}\n";
echo "- Status: {$task['status']}\n";
echo "- Assigned to: {$task['user_name']}\n";
echo "- Pivot: {$task['pivot_name']}\n";
echo "\n";

// Complete task
$completed = TaskOperations::completeTask($taskId, 1); // Completed by admin (ID: 1)
echo "Completed task: $completed row(s) affected\n";
$task = TaskOperations::getTaskById($taskId);
echo "Updated status: {$task['status']}\n";
echo "Completed by: {$task['completed_by_name']}\n\n";

// Example: Alert Operations
echo "=== Alert Operations ===\n";

// Create a new alert
$alertId = AlertOperations::createAlert([
    'pivot_id' => $pivotId,
    'type' => 'moisture_low',
    'title' => 'Alert Example',
    'message' => 'This is an example alert',
    'details' => json_encode(['current' => 35.2, 'threshold' => 40.0]),
    'task_id' => $taskId,
    'is_resolved' => 0
]);
echo "Created new alert with ID: $alertId\n\n";

// Get alert by ID
$alert = AlertOperations::getAlertById($alertId);
echo "Alert details:\n";
echo "- Title: {$alert['title']}\n";
echo "- Type: {$alert['type']}\n";
echo "- Message: {$alert['message']}\n";
echo "- Pivot: {$alert['pivot_name']}\n";
echo "- Related Task: {$alert['task_title']}\n";
echo "- Resolved: " . ($alert['is_resolved'] ? 'Yes' : 'No') . "\n";
echo "\n";

// Resolve alert
$resolved = AlertOperations::resolveAlert($alertId, 1, 'Action taken to resolve the alert'); // Resolved by admin (ID: 1)
echo "Resolved alert: $resolved row(s) affected\n";
$alert = AlertOperations::getAlertById($alertId);
echo "Updated resolved status: " . ($alert['is_resolved'] ? 'Yes' : 'No') . "\n";
echo "Resolved by: {$alert['resolved_by_name']}\n\n";

// Example: History Operations
echo "=== History Operations ===\n";

// Get all history records
$history = HistoryOperations::getAllHistory();
echo "All history records:\n";
foreach ($history as $record) {
    echo "- {$record['created_at']}: {$record['description']} (by {$record['user_name']})\n";
}
echo "\n";

// Example: User Preferences Operations
echo "=== User Preferences Operations ===\n";

// Get user preferences
$preferences = UserPreferencesOperations::getUserPreferences($newUserId);
echo "User preferences:\n";
echo "- Language: {$preferences['language']}\n";
echo "- Theme: {$preferences['theme']}\n";
echo "\n";

// Update user preferences
$updated = UserPreferencesOperations::updateUserPreferences($newUserId, [
    'language' => 'en',
    'theme' => 'dark'
]);
echo "Updated user preferences: $updated row(s) affected\n";
$preferences = UserPreferencesOperations::getUserPreferences($newUserId);
echo "Updated language: {$preferences['language']}\n";
echo "Updated theme: {$preferences['theme']}\n\n";

// Example: Weather Settings Operations
echo "=== Weather Settings Operations ===\n";

// Update weather settings
$updated = WeatherSettingsOperations::updateWeatherSettings($newUserId, [
    'api_provider' => 'OpenWeatherMap',
    'api_key' => 'example_api_key',
    'location' => 'Béchar, Algeria',
    'is_active' => 1
]);
echo "Updated weather settings: $updated row(s) affected\n";
$settings = WeatherSettingsOperations::getWeatherSettings($newUserId);
echo "Weather settings:\n";
echo "- API Provider: {$settings['api_provider']}\n";
echo "- API Key: {$settings['api_key']}\n";
echo "- Location: {$settings['location']}\n";
echo "- Active: " . ($settings['is_active'] ? 'Yes' : 'No') . "\n";
echo "\n";

// Clean up example data
echo "=== Cleaning Up ===\n";

// Delete alert
$deleted = AlertOperations::deleteAlert($alertId);
echo "Deleted alert: $deleted row(s) affected\n";

// Delete task
$deleted = TaskOperations::deleteTask($taskId);
echo "Deleted task: $deleted row(s) affected\n";

// Delete pivot
$deleted = PivotOperations::deletePivot($pivotId);
echo "Deleted pivot: $deleted row(s) affected\n";

// Delete user
$deleted = UserOperations::deleteUser($newUserId);
echo "Deleted user: $deleted row(s) affected\n";

echo "\nExample completed successfully!\n";
