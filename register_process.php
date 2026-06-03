<?php
/**
 * Register process page
 * 
 * Handles registration form submission. Validates input, creates user account,
 * and redirects to login page on success or back to register with error on failure.
 */

require_once 'include/bootstrap.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: posts.php');
    exit;
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Get and sanitize input
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['passwordConfirm'] ?? '';

// Validate inputs
if (empty($email) || empty($username) || empty($password) || empty($passwordConfirm)) {
    header('Location: register.php?error=' . urlencode('All fields are required'));
    exit;
}

// Check password confirmation
if ($password !== $passwordConfirm) {
    header('Location: register.php?error=' . urlencode('Passwords do not match'));
    exit;
}

// Attempt registration
$result = registerUser($pdo, $email, $username, $password);

if (!$result['success']) {
    header('Location: register.php?error=' . urlencode($result['message']));
    exit;
}

// Redirect to login with success message
header('Location: login.php?success=' . urlencode('Registration successful! Please log in.'));
exit;
?>
