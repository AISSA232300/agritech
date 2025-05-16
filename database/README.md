# SQLite Database for AgriTech Béchar

This directory contains the SQLite database for the AgriTech Béchar project.

## Database File

The main database file is `agritech.sqlite`. This is a binary file that contains all the tables and data for the application.

## Database Structure

The database contains the following tables:

1. **roles** - User roles (admin, gestionaire, emploie)
2. **users** - User accounts with authentication information
3. **user_preferences** - User preferences like language and theme
4. **weather_settings** - Weather API settings
5. **pivots** - Agricultural pivots/fields
6. **tasks** - Farming tasks
7. **alerts** - System alerts and notifications
8. **historique** - History of completed tasks and resolved alerts

## Default Data

The database comes with the following default data:

- **Roles**: admin, gestionaire, emploie
- **Admin User**: Username: admin, Password: admin123

## Accessing the Database

### Using PHP

The `db_connect.php` file provides helper functions to interact with the database:

```php
// Include the database connection helper
require_once __DIR__ . '/db_connect.php';

// Query examples
$users = query("SELECT * FROM users");
$user = queryOne("SELECT * FROM users WHERE username = ?", ['admin']);
$count = queryValue("SELECT COUNT(*) FROM users");

// Insert example
$id = insert('users', [
    'role_id' => 2,
    'name' => 'New User',
    'username' => 'newuser',
    'email' => 'newuser@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'is_active' => 1
]);

// Update example
$updated = update('users', 
    ['is_active' => 0], 
    'id = :id', 
    ['id' => 2]
);

// Delete example
$deleted = delete('tasks', 'id = ?', [1]);
```

### Using SQLite Command Line

You can also use the SQLite command line tool to interact with the database:

```bash
# Open the database
sqlite3 agritech.sqlite

# Show tables
.tables

# Show schema
.schema

# Run a query
SELECT * FROM users;

# Exit
.exit
```

### Using External Tools

You can use external tools like DB Browser for SQLite, SQLiteStudio, or DBeaver to open and manage the SQLite database file.
