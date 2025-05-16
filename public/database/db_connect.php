<?php
/**
 * Database connection helper for AgriTech Béchar
 * Provides functions to connect to the database and execute queries
 */

/**
 * Get a PDO connection to the database
 * @return PDO The database connection
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            // SQLite database path
            $dbPath = __DIR__ . '/../../database/database.sqlite';
            
            // Create PDO connection
            $pdo = new PDO('sqlite:' . $dbPath);
            
            // Set error mode to exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative array
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Enable foreign keys
            $pdo->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Execute a SELECT query and return all results
 * @param string $sql The SQL query
 * @param array $params The query parameters
 * @return array The query results
 */
function query($sql, $params = []) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Query error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Execute a SELECT query and return a single row
 * @param string $sql The SQL query
 * @param array $params The query parameters
 * @return array|null The query result or null if not found
 */
function queryOne($sql, $params = []) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    } catch (PDOException $e) {
        error_log('Query error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Execute an INSERT query
 * @param string $table The table name
 * @param array $data The data to insert
 * @return int|false The last insert ID or false on failure
 */
function insert($table, $data) {
    try {
        $pdo = getDbConnection();
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('Insert error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Execute an UPDATE query
 * @param string $table The table name
 * @param array $data The data to update
 * @param string $where The WHERE clause
 * @param array $whereParams The WHERE clause parameters
 * @return bool True on success, false on failure
 */
function update($table, $data, $where, $whereParams = []) {
    try {
        $pdo = getDbConnection();
        
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = :$column";
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE $where";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind data parameters
        foreach ($data as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        
        // Bind where parameters
        foreach ($whereParams as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log('Update error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Execute a DELETE query
 * @param string $table The table name
 * @param string $where The WHERE clause
 * @param array $params The WHERE clause parameters
 * @return bool True on success, false on failure
 */
function delete($table, $where, $params = []) {
    try {
        $pdo = getDbConnection();
        
        $sql = "DELETE FROM $table WHERE $where";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log('Delete error: ' . $e->getMessage());
        return false;
    }
}
