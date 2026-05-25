<?php
/**
 * Login process page
 * 
 * Handles login form submission. Validates credentials and creates session.
 * Redirects to posts.php on success, or back to login.php with error on failure.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: posts.php');
    exit;
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Get and sanitize input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    header('Location: login.php?error=' . urlencode('Email and password are required'));
    exit;
}

// Attempt login
$result = loginUser($pdo, $email, $password);

if (!$result['success']) {
    header('Location: login.php?error=' . urlencode($result['message']));
    exit;
}

// Create session for logged-in user
$_SESSION['user_id'] = $result['user_id'];

// Redirect to posts page
header('Location: posts.php');
exit;
?>
