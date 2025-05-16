<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=agritech_bechar', 'root', 'rootabcd');
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}