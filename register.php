<?php
/**
 * Register page
 * 
 * Displays registration form. Redirects logged-in users to posts.php.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Redirect logged-in users
requireGuest();

$pageTitle = 'Register - TalkBox';
include __DIR__ . '/include/views/header.php';
include __DIR__ . '/include/views/register.php';
include __DIR__ . '/include/views/footer.php';
?>
