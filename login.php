<?php
/**
 * Login page
 * 
 * Displays login form. Redirects logged-in users to posts.php.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Redirect logged-in users
requireGuest();

$pageTitle = 'Login - TalkBox';
include __DIR__ . '/include/views/header.php';
include __DIR__ . '/include/views/login.php';
include __DIR__ . '/include/views/footer.php';
?>
