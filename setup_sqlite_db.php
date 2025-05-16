<?php

// Database file path
$dbFile = __DIR__ . '/database/agritech.sqlite';

// Check if database file exists
if (!file_exists($dbFile)) {
    echo "Creating database file...\n";
    touch($dbFile);
}

try {
    // Connect to SQLite database
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable foreign keys
    $pdo->exec('PRAGMA foreign_keys = ON');
    
    echo "Connected to SQLite database successfully!\n";
    
    // Create roles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Created roles table.\n";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            role_id INTEGER,
            name TEXT NOT NULL,
            username TEXT UNIQUE NOT NULL,
            email TEXT UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password TEXT NOT NULL,
            profile_photo TEXT NULL,
            is_active INTEGER DEFAULT 1,
            remember_token TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(id)
        )
    ");
    echo "Created users table.\n";
    
    // Create user_preferences table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_preferences (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            language TEXT DEFAULT 'fr',
            theme TEXT DEFAULT 'light',
            dashboard_widgets TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
    echo "Created user_preferences table.\n";
    
    // Create weather_settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS weather_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            api_provider TEXT NOT NULL,
            api_key TEXT NOT NULL,
            location TEXT DEFAULT 'Béchar, Algeria',
            is_active INTEGER DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
    echo "Created weather_settings table.\n";
    
    // Create pivots table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pivots (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            surface_area REAL NOT NULL,
            crop_type TEXT NOT NULL,
            location TEXT NULL,
            notes TEXT NULL,
            status TEXT DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Created pivots table.\n";
    
    // Create tasks table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            pivot_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            description TEXT NULL,
            priority TEXT DEFAULT 'medium',
            status TEXT DEFAULT 'pending',
            due_date TIMESTAMP NULL,
            completed_at TIMESTAMP NULL,
            completed_by INTEGER NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (pivot_id) REFERENCES pivots(id) ON DELETE CASCADE,
            FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created tasks table.\n";
    
    // Create alerts table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS alerts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pivot_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            title TEXT NOT NULL,
            message TEXT NOT NULL,
            details TEXT NULL,
            action_taken TEXT NULL,
            task_id INTEGER NULL,
            is_resolved INTEGER DEFAULT 0,
            resolved_at TIMESTAMP NULL,
            resolved_by INTEGER NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (pivot_id) REFERENCES pivots(id) ON DELETE CASCADE,
            FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL,
            FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created alerts table.\n";
    
    // Create historique table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS historique (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            task_id INTEGER NULL,
            alert_id INTEGER NULL,
            action_type TEXT NOT NULL,
            description TEXT NOT NULL,
            details TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL,
            FOREIGN KEY (alert_id) REFERENCES alerts(id) ON DELETE SET NULL
        )
    ");
    echo "Created historique table.\n";
    
    // Insert default roles if they don't exist
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles");
    $stmt->execute();
    $roleCount = $stmt->fetchColumn();
    
    if ($roleCount == 0) {
        $pdo->exec("
            INSERT INTO roles (name, description) VALUES
            ('admin', 'Administrateur avec accès complet'),
            ('gestionaire', 'Gestionnaire des ressources agricoles'),
            ('emploie', 'Employé agricole')
        ");
        echo "Inserted default roles.\n";
    }
    
    // Insert default admin user if it doesn't exist
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    $adminCount = $stmt->fetchColumn();
    
    if ($adminCount == 0) {
        // Password: admin123 (hashed)
        $pdo->exec("
            INSERT INTO users (role_id, name, username, email, password, is_active) VALUES
            (1, 'Administrateur', 'admin', 'admin@agritech-bechar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1)
        ");
        echo "Inserted default admin user.\n";
        
        // Insert user preferences for admin
        $pdo->exec("
            INSERT INTO user_preferences (user_id, language, theme) VALUES
            (1, 'fr', 'light')
        ");
        echo "Inserted default user preferences for admin.\n";
    }
    
    echo "All tables created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
