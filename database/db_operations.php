<?php

/**
 * Database Operations
 *
 * This file provides functions to handle database operations for each table,
 * ensuring that actions like removing or editing records are properly reflected
 * in the database.
 */

// Include the database connection helper
require_once __DIR__ . '/db_connect.php';

/**
 * User Operations
 */
class UserOperations {
    /**
     * Get all users
     *
     * @return array Array of users
     */
    public static function getAllUsers() {
        try {
            // First check if tables exist
            $pdo = getDbConnection();
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('users', $tables)) {
                error_log("Users table does not exist");
                return [];
            }

            if (!in_array('roles', $tables)) {
                error_log("Roles table does not exist, returning users without role names");
                return query("SELECT * FROM users ORDER BY name");
            }

            // Get users with roles
            $users = query("
                SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.name
            ");

            error_log("Retrieved " . count($users) . " users from database");
            return $users;
        } catch (Exception $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user by ID
     *
     * @param int $id User ID
     * @return array|null User data or null if not found
     */
    public static function getUserById($id) {
        try {
            // First check if tables exist
            $pdo = getDbConnection();
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('users', $tables)) {
                error_log("Users table does not exist when getting user by ID");
                return null;
            }

            if (!in_array('roles', $tables)) {
                error_log("Roles table does not exist, returning user without role name");
                return queryOne("SELECT * FROM users WHERE id = ?", [$id]);
            }

            // Get user with role
            $user = queryOne("
                SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.id = ?
            ", [$id]);

            if ($user) {
                error_log("Retrieved user with ID: " . $id);
            } else {
                error_log("No user found with ID: " . $id);
            }

            return $user;
        } catch (Exception $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by username
     *
     * @param string $username Username
     * @return array|null User data or null if not found
     */
    public static function getUserByUsername($username) {
        try {
            // First check if tables exist
            $pdo = getDbConnection();
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('users', $tables)) {
                error_log("Users table does not exist when getting user by username");
                return null;
            }

            if (!in_array('roles', $tables)) {
                error_log("Roles table does not exist, returning user without role name");
                return queryOne("SELECT * FROM users WHERE username = ?", [$username]);
            }

            // Get user with role
            $user = queryOne("
                SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.username = ?
            ", [$username]);

            if ($user) {
                error_log("Retrieved user with username: " . $username);
            } else {
                error_log("No user found with username: " . $username);
            }

            return $user;
        } catch (Exception $e) {
            error_log("Error in getUserByUsername: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new user
     *
     * @param array $userData User data
     * @return int New user ID
     */
    public static function createUser($userData) {
        // Hash password if provided
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }

        // Set default values
        if (!isset($userData['is_active'])) {
            $userData['is_active'] = 1;
        }

        // Set default status
        if (!isset($userData['status'])) {
            $userData['status'] = 'Hors ligne';
        }

        // Generate a unique ID if not provided
        if (!isset($userData['id'])) {
            $userData['id'] = time() . rand(1000, 9999);
        }

        $userId = insert('users', $userData);

        // Create default user preferences
        if ($userId) {
            insert('user_preferences', [
                'user_id' => $userId,
                'language' => 'fr',
                'theme' => 'light'
            ]);
        }

        return $userId;
    }

    /**
     * Update a user
     *
     * @param int $id User ID
     * @param array $userData User data
     * @return int Number of affected rows
     */
    public static function updateUser($id, $userData) {
        // Hash password if provided and not empty
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        } elseif (isset($userData['password']) && empty($userData['password'])) {
            // If password is empty, remove it from the update data
            unset($userData['password']);
        }

        // Update the user
        return update('users', $userData, 'id = :id', ['id' => $id]);
    }

    /**
     * Update user status
     *
     * @param int $id User ID
     * @param string $status New status ('En ligne' or 'Hors ligne')
     * @return int Number of affected rows
     */
    public static function updateUserStatus($id, $status) {
        return update('users', ['status' => $status], 'id = :id', ['id' => $id]);
    }

    /**
     * Delete a user
     *
     * @param int $id User ID
     * @return int Number of affected rows
     */
    public static function deleteUser($id) {
        // Delete related records first
        execute("DELETE FROM weather_settings WHERE user_id = ?", [$id]);
        execute("DELETE FROM user_preferences WHERE user_id = ?", [$id]);

        // Now delete the user (will cascade to other related records)
        return delete('users', 'id = ?', [$id]);
    }

    /**
     * Authenticate a user
     *
     * @param string $username Username
     * @param string $password Password
     * @return array|null User data or null if authentication fails
     */
    public static function authenticate($username, $password) {
        try {
            error_log("Authenticating user: $username");
            $user = self::getUserByUsername($username);

            if (!$user) {
                error_log("Authentication failed: User not found: $username");
                return null;
            }

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
                    self::updateUser($user['id'], ['password' => $hashedPassword]);
                    error_log("Updated user password with secure hash");
                }
            }

            if ($passwordValid) {
                error_log("Authentication successful for user: $username");
                return $user;
            }

            error_log("Authentication failed: Invalid password for user: $username");
            return null;
        } catch (Exception $e) {
            error_log("Error in authenticate: " . $e->getMessage());
            return null;
        }
    }
}

/**
 * Pivot Operations
 */
class PivotOperations {
    /**
     * Get all pivots
     *
     * @param int|null $userId Optional user ID to filter by
     * @return array Array of pivots
     */
    public static function getAllPivots($userId = null) {
        if ($userId) {
            return query("
                SELECT p.*, u.name as user_name,
                (SELECT COUNT(*) FROM tasks t WHERE t.pivot_id = p.id) as task_count
                FROM pivots p
                JOIN users u ON p.user_id = u.id
                WHERE p.user_id = ?
                ORDER BY p.name
            ", [$userId]);
        } else {
            return query("
                SELECT p.*, u.name as user_name,
                (SELECT COUNT(*) FROM tasks t WHERE t.pivot_id = p.id) as task_count
                FROM pivots p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.name
            ");
        }
    }

    /**
     * Get pivot by ID
     *
     * @param int $id Pivot ID
     * @return array|null Pivot data or null if not found
     */
    public static function getPivotById($id) {
        return queryOne("
            SELECT p.*, u.name as user_name
            FROM pivots p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = ?
        ", [$id]);
    }

    /**
     * Create a new pivot
     *
     * @param array $pivotData Pivot data
     * @return int New pivot ID
     */
    public static function createPivot($pivotData) {
        return insert('pivots', $pivotData);
    }

    /**
     * Update a pivot
     *
     * @param int $id Pivot ID
     * @param array $pivotData Pivot data
     * @return int Number of affected rows
     */
    public static function updatePivot($id, $pivotData) {
        return update('pivots', $pivotData, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete a pivot
     *
     * @param int $id Pivot ID
     * @return int Number of affected rows
     */
    public static function deletePivot($id) {
        // Delete the pivot (will cascade to related records)
        return delete('pivots', 'id = ?', [$id]);
    }
}

/**
 * Task Operations
 */
class TaskOperations {
    /**
     * Get all tasks
     *
     * @param int|null $userId Optional user ID to filter by
     * @param int|null $pivotId Optional pivot ID to filter by
     * @param string|null $status Optional status to filter by
     * @return array Array of tasks
     */
    public static function getAllTasks($userId = null, $pivotId = null, $status = null) {
        $query = "
            SELECT t.*, u.name as user_name, p.name as pivot_name,
            cu.name as completed_by_name
            FROM tasks t
            JOIN users u ON t.user_id = u.id
            JOIN pivots p ON t.pivot_id = p.id
            LEFT JOIN users cu ON t.completed_by = cu.id
            WHERE 1=1
        ";
        $params = [];

        if ($userId) {
            $query .= " AND t.user_id = ?";
            $params[] = $userId;
        }

        if ($pivotId) {
            $query .= " AND t.pivot_id = ?";
            $params[] = $pivotId;
        }

        if ($status) {
            $query .= " AND t.status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY t.due_date, t.priority DESC";

        return query($query, $params);
    }

    /**
     * Get task by ID
     *
     * @param int $id Task ID
     * @return array|null Task data or null if not found
     */
    public static function getTaskById($id) {
        return queryOne("
            SELECT t.*, u.name as user_name, p.name as pivot_name,
            cu.name as completed_by_name
            FROM tasks t
            JOIN users u ON t.user_id = u.id
            JOIN pivots p ON t.pivot_id = p.id
            LEFT JOIN users cu ON t.completed_by = cu.id
            WHERE t.id = ?
        ", [$id]);
    }

    /**
     * Create a new task
     *
     * @param array $taskData Task data
     * @return int New task ID
     */
    public static function createTask($taskData) {
        return insert('tasks', $taskData);
    }

    /**
     * Update a task
     *
     * @param int $id Task ID
     * @param array $taskData Task data
     * @return int Number of affected rows
     */
    public static function updateTask($id, $taskData) {
        return update('tasks', $taskData, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete a task
     *
     * @param int $id Task ID
     * @return int Number of affected rows
     */
    public static function deleteTask($id) {
        return delete('tasks', 'id = ?', [$id]);
    }

    /**
     * Mark a task as completed
     *
     * @param int $id Task ID
     * @param int $completedBy User ID of the user who completed the task
     * @return int Number of affected rows
     */
    public static function completeTask($id, $completedBy) {
        $taskData = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'completed_by' => $completedBy
        ];

        $result = self::updateTask($id, $taskData);

        if ($result) {
            // Add to history
            $task = self::getTaskById($id);

            if ($task) {
                insert('historique', [
                    'user_id' => $completedBy,
                    'task_id' => $id,
                    'action_type' => 'task_completed',
                    'description' => "Tâche complétée: {$task['title']}",
                    'details' => json_encode($task)
                ]);
            }
        }

        return $result;
    }
}

/**
 * Alert Operations
 */
class AlertOperations {
    /**
     * Get all alerts
     *
     * @param int|null $pivotId Optional pivot ID to filter by
     * @param bool|null $isResolved Optional resolved status to filter by
     * @return array Array of alerts
     */
    public static function getAllAlerts($pivotId = null, $isResolved = null) {
        $query = "
            SELECT a.*, p.name as pivot_name, t.title as task_title,
            u.name as resolved_by_name
            FROM alerts a
            JOIN pivots p ON a.pivot_id = p.id
            LEFT JOIN tasks t ON a.task_id = t.id
            LEFT JOIN users u ON a.resolved_by = u.id
            WHERE 1=1
        ";
        $params = [];

        if ($pivotId) {
            $query .= " AND a.pivot_id = ?";
            $params[] = $pivotId;
        }

        if ($isResolved !== null) {
            $query .= " AND a.is_resolved = ?";
            $params[] = $isResolved ? 1 : 0;
        }

        $query .= " ORDER BY a.created_at DESC";

        return query($query, $params);
    }

    /**
     * Get alert by ID
     *
     * @param int $id Alert ID
     * @return array|null Alert data or null if not found
     */
    public static function getAlertById($id) {
        return queryOne("
            SELECT a.*, p.name as pivot_name, t.title as task_title,
            u.name as resolved_by_name
            FROM alerts a
            JOIN pivots p ON a.pivot_id = p.id
            LEFT JOIN tasks t ON a.task_id = t.id
            LEFT JOIN users u ON a.resolved_by = u.id
            WHERE a.id = ?
        ", [$id]);
    }

    /**
     * Create a new alert
     *
     * @param array $alertData Alert data
     * @return int New alert ID
     */
    public static function createAlert($alertData) {
        return insert('alerts', $alertData);
    }

    /**
     * Update an alert
     *
     * @param int $id Alert ID
     * @param array $alertData Alert data
     * @return int Number of affected rows
     */
    public static function updateAlert($id, $alertData) {
        return update('alerts', $alertData, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete an alert
     *
     * @param int $id Alert ID
     * @return int Number of affected rows
     */
    public static function deleteAlert($id) {
        return delete('alerts', 'id = ?', [$id]);
    }

    /**
     * Resolve an alert
     *
     * @param int $id Alert ID
     * @param int $resolvedBy User ID of the user who resolved the alert
     * @param string $actionTaken Action taken to resolve the alert
     * @return int Number of affected rows
     */
    public static function resolveAlert($id, $resolvedBy, $actionTaken) {
        $alertData = [
            'is_resolved' => 1,
            'resolved_at' => date('Y-m-d H:i:s'),
            'resolved_by' => $resolvedBy,
            'action_taken' => $actionTaken
        ];

        $result = self::updateAlert($id, $alertData);

        if ($result) {
            // Add to history
            $alert = self::getAlertById($id);

            if ($alert) {
                insert('historique', [
                    'user_id' => $resolvedBy,
                    'alert_id' => $id,
                    'action_type' => 'alert_resolved',
                    'description' => "Alerte résolue: {$alert['title']}",
                    'details' => json_encode($alert)
                ]);
            }
        }

        return $result;
    }
}

/**
 * History Operations
 */
class HistoryOperations {
    /**
     * Get all history records
     *
     * @param int|null $userId Optional user ID to filter by
     * @param string|null $actionType Optional action type to filter by
     * @param string|null $startDate Optional start date to filter by (YYYY-MM-DD)
     * @param string|null $endDate Optional end date to filter by (YYYY-MM-DD)
     * @return array Array of history records
     */
    public static function getAllHistory($userId = null, $actionType = null, $startDate = null, $endDate = null) {
        $query = "
            SELECT h.*, u.name as user_name,
            t.title as task_title, a.title as alert_title
            FROM historique h
            JOIN users u ON h.user_id = u.id
            LEFT JOIN tasks t ON h.task_id = t.id
            LEFT JOIN alerts a ON h.alert_id = a.id
            WHERE 1=1
        ";
        $params = [];

        if ($userId) {
            $query .= " AND h.user_id = ?";
            $params[] = $userId;
        }

        if ($actionType) {
            $query .= " AND h.action_type = ?";
            $params[] = $actionType;
        }

        if ($startDate) {
            $query .= " AND DATE(h.created_at) >= ?";
            $params[] = $startDate;
        }

        if ($endDate) {
            $query .= " AND DATE(h.created_at) <= ?";
            $params[] = $endDate;
        }

        $query .= " ORDER BY h.created_at DESC";

        return query($query, $params);
    }

    /**
     * Get history by ID
     *
     * @param int $id History ID
     * @return array|null History data or null if not found
     */
    public static function getHistoryById($id) {
        return queryOne("
            SELECT h.*, u.name as user_name,
            t.title as task_title, a.title as alert_title
            FROM historique h
            JOIN users u ON h.user_id = u.id
            LEFT JOIN tasks t ON h.task_id = t.id
            LEFT JOIN alerts a ON h.alert_id = a.id
            WHERE h.id = ?
        ", [$id]);
    }

    /**
     * Add a history record
     *
     * @param array $historyData History data
     * @return int New history ID
     */
    public static function addHistory($historyData) {
        return insert('historique', $historyData);
    }
}

/**
 * User Preferences Operations
 */
class UserPreferencesOperations {
    /**
     * Get user preferences
     *
     * @param int $userId User ID
     * @return array|null User preferences or null if not found
     */
    public static function getUserPreferences($userId) {
        return queryOne("SELECT * FROM user_preferences WHERE user_id = ?", [$userId]);
    }

    /**
     * Update user preferences
     *
     * @param int $userId User ID
     * @param array $preferencesData Preferences data
     * @return int Number of affected rows
     */
    public static function updateUserPreferences($userId, $preferencesData) {
        // Check if preferences exist
        $preferences = self::getUserPreferences($userId);

        if ($preferences) {
            // Update existing preferences
            return update('user_preferences', $preferencesData, 'user_id = :user_id', ['user_id' => $userId]);
        } else {
            // Create new preferences
            $preferencesData['user_id'] = $userId;
            return insert('user_preferences', $preferencesData);
        }
    }
}

/**
 * Weather Settings Operations
 */
class WeatherSettingsOperations {
    /**
     * Get weather settings
     *
     * @param int $userId User ID
     * @return array|null Weather settings or null if not found
     */
    public static function getWeatherSettings($userId) {
        return queryOne("SELECT * FROM weather_settings WHERE user_id = ?", [$userId]);
    }

    /**
     * Update weather settings
     *
     * @param int $userId User ID
     * @param array $settingsData Settings data
     * @return int Number of affected rows
     */
    public static function updateWeatherSettings($userId, $settingsData) {
        // Check if settings exist
        $settings = self::getWeatherSettings($userId);

        if ($settings) {
            // Update existing settings
            return update('weather_settings', $settingsData, 'user_id = :user_id', ['user_id' => $userId]);
        } else {
            // Create new settings
            $settingsData['user_id'] = $userId;
            return insert('weather_settings', $settingsData);
        }
    }
}
