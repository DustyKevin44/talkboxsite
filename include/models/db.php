<?php
/**
 * Database connection and initialization
 * 
 * This file creates a PDO connection to SQLite database and initializes tables
 * if they don't exist. All database operations use prepared statements for security.
 */

// Define database path
$dbPath = __DIR__ . '/../../db/database.db';

try {
    // Create PDO connection to SQLite database
    $pdo = new PDO('sqlite:' . $dbPath);
    
    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable foreign keys
    $pdo->exec('PRAGMA foreign_keys = ON');
    
    // Create tables if they don't exist
    createTables($pdo);
    
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

/**
 * Create tables in the database if they don't already exist
 * 
 * @param PDO $pdo Database connection object
 * @return void
 */
function createTables($pdo) {
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            profile_image TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Create posts table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            message TEXT NOT NULL,
            image_path TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
}
?>
