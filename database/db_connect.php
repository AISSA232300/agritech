<?php

/**
 * Database connection helper
 *
 * This file provides a simple way to connect to the SQLite database
 * and execute queries.
 */

/**
 * Get a PDO connection to the SQLite database
 *
 * @return PDO The database connection
 */
function getDbConnection() {
    $dbFile = __DIR__ . '/agritech.sqlite';

    // Check if database file exists
    if (!file_exists($dbFile)) {
        error_log("Database file not found: " . $dbFile);

        // Create the database file and initialize it
        try {
            // Create a new PDO connection
            $pdo = new PDO('sqlite:' . $dbFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA foreign_keys = ON');

            // Create tables if they don't exist
            createTables($pdo);

            error_log("Database created successfully");
            return $pdo;
        } catch (PDOException $e) {
            error_log("Failed to create database: " . $e->getMessage());
            die("Database creation failed: " . $e->getMessage());
        }
    }

    try {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA foreign_keys = ON');

        // Check if tables exist
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('users', $tables) || !in_array('roles', $tables)) {
            error_log("Tables missing, creating them");
            createTables($pdo);
        }

        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Create the necessary tables in the database
 *
 * @param PDO $pdo The database connection
 */
function createTables($pdo) {
    // Create roles table
    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role_id INTEGER NOT NULL,
        status TEXT DEFAULT 'Hors ligne',
        is_active INTEGER DEFAULT 1,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    )");

    // Create user_preferences table
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_preferences (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        language TEXT DEFAULT 'fr',
        theme TEXT DEFAULT 'light',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Insert default roles if they don't exist
    $roles = [
        ['id' => 1, 'name' => 'admin'],
        ['id' => 2, 'name' => 'gestionnaire'],
        ['id' => 3, 'name' => 'employe']
    ];

    $stmt = $pdo->prepare("INSERT OR IGNORE INTO roles (id, name) VALUES (?, ?)");
    foreach ($roles as $role) {
        $stmt->execute([$role['id'], $role['name']]);
    }

    // Insert a default admin user if no users exist
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (name, username, email, password, role_id, status, is_active)
                    VALUES ('Admin', 'admin', 'admin@example.com', '$adminPassword', 1, 'Hors ligne', 1)");

        // Create default preferences for admin
        $adminId = $pdo->lastInsertId();
        $pdo->exec("INSERT INTO user_preferences (user_id, language, theme)
                    VALUES ($adminId, 'fr', 'light')");
    }
}

/**
 * Execute a query and return all results
 *
 * @param string $query The SQL query to execute
 * @param array $params Parameters for the query
 * @return array The query results
 */
function query($query, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Execute a query and return a single row
 *
 * @param string $query The SQL query to execute
 * @param array $params Parameters for the query
 * @return array|null The first row of results or null if no results
 */
function queryOne($query, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result !== false ? $result : null;
}

/**
 * Execute a query and return a single value
 *
 * @param string $query The SQL query to execute
 * @param array $params Parameters for the query
 * @return mixed The first column of the first row of results
 */
function queryValue($query, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

/**
 * Execute an insert, update, or delete query
 *
 * @param string $query The SQL query to execute
 * @param array $params Parameters for the query
 * @return int The number of affected rows
 */
function execute($query, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Insert a record into a table
 *
 * @param string $table The table name
 * @param array $data Associative array of column => value pairs
 * @return int The ID of the inserted record
 */
function insert($table, $data) {
    $pdo = getDbConnection();

    $columns = array_keys($data);
    $placeholders = array_map(function($col) { return ":$col"; }, $columns);

    $query = "INSERT INTO $table (" . implode(', ', $columns) . ")
              VALUES (" . implode(', ', $placeholders) . ")";

    $stmt = $pdo->prepare($query);

    foreach ($data as $column => $value) {
        $stmt->bindValue(":$column", $value);
    }

    $stmt->execute();
    return $pdo->lastInsertId();
}

/**
 * Update records in a table
 *
 * @param string $table The table name
 * @param array $data Associative array of column => value pairs to update
 * @param string $where The WHERE clause
 * @param array $whereParams Parameters for the WHERE clause
 * @return int The number of affected rows
 */
function update($table, $data, $where, $whereParams = []) {
    $pdo = getDbConnection();

    $setClauses = array_map(function($col) { return "$col = :$col"; }, array_keys($data));

    $query = "UPDATE $table SET " . implode(', ', $setClauses) . " WHERE $where";

    $stmt = $pdo->prepare($query);

    foreach ($data as $column => $value) {
        $stmt->bindValue(":$column", $value);
    }

    foreach ($whereParams as $param => $value) {
        $stmt->bindValue(":$param", $value);
    }

    $stmt->execute();
    return $stmt->rowCount();
}

/**
 * Delete records from a table
 *
 * @param string $table The table name
 * @param string $where The WHERE clause
 * @param array $params Parameters for the WHERE clause
 * @return int The number of affected rows
 */
function delete($table, $where, $params = []) {
    $pdo = getDbConnection();
    $query = "DELETE FROM $table WHERE $where";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}
