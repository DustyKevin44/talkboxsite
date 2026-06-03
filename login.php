<?php
/**
 * Login page
 * 
 * Displays login form. Redirects logged-in users to posts.php.
 */

require_once 'include/bootstrap.php';

// Redirect logged-in users
requireGuest();

$pageTitle = 'Login - TalkBox';
include 'include/views/_header.php';
include 'include/views/_login.php';
include 'include/views/_footer.php';
?>
