<?php

// Test different connection methods
$dbname = 'agritech_bechar';
$username = 'root';
$password = '';

// Common socket paths for different systems
$socketPaths = [
    '/tmp/mysql.sock',
    '/var/run/mysqld/mysqld.sock',
    '/var/lib/mysql/mysql.sock',
    '/opt/local/var/run/mysql8/mysqld.sock',
    '/opt/local/var/run/mysql57/mysqld.sock',
    '/opt/local/var/run/mysql56/mysqld.sock',
    '/opt/local/var/run/mysql5/mysqld.sock'
];

echo "Testing MySQL connection...\n";

// First try TCP/IP connection with port 3306
echo "Trying TCP/IP connection with port 8080...\n";
try {
    $conn = new PDO("mysql:host=127.0.0.1;port=8080;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "SUCCESS: Connected to MySQL via TCP/IP\n";

    // Test if we can query the database
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables in database: " . implode(", ", $tables) . "\n";

    // Close connection
    $conn = null;
} catch (PDOException $e) {
    echo "FAILED TCP/IP: " . $e->getMessage() . "\n";

    // Try socket connections
    echo "\nTrying socket connections...\n";
    $connected = false;

    foreach ($socketPaths as $socket) {
        echo "Trying socket: $socket\n";
        try {
            $conn = new PDO("mysql:unix_socket=$socket;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "SUCCESS: Connected to MySQL via socket: $socket\n";

            // Test if we can query the database
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            echo "Tables in database: " . implode(", ", $tables) . "\n";

            // Close connection
            $conn = null;
            $connected = true;
            break;
        } catch (PDOException $e2) {
            echo "FAILED socket $socket: " . $e2->getMessage() . "\n";
        }
    }

    if (!$connected) {
        echo "\nAll connection attempts failed.\n";
    }
}

echo "Connection tests completed.\n";
