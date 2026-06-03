<?php
/**
 * Bootstrap file - initializes the application
 * 
 * This file starts the session, sets error handling, and includes all necessary
 * model files and utilities. It should be included at the top of every page.
 */

// Start session for user authentication
session_start();

// Set error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Include database connection
require_once 'models/db.php';

// Include model classes
require_once 'models/user.php';
require_once 'models/post.php';

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get the currently logged-in user's ID
 * 
 * @return int|null The user ID if logged in, null otherwise
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Redirect to login if not authenticated
 * Used to protect pages that require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Redirect to home if already logged in
 * Used on login/register pages to prevent logged-in users from accessing them
 */
function requireGuest() {
    if (isLoggedIn()) {
        header('Location: posts.php');
        exit;
    }
}

/**
 * Sanitize output to prevent XSS attacks
 * 
 * @param string $text The text to sanitize
 * @return string The sanitized text
 */
function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
