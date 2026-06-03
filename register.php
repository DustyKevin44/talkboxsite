<?php
/**
 * Register page
 * 
 * Displays registration form. Redirects logged-in users to posts.php.
 */

require_once 'include/bootstrap.php';

// Redirect logged-in users
requireGuest();

$pageTitle = 'Register - TalkBox';
include 'include/views/_header.php';
include 'include/views/_register.php';
include 'include/views/_footer.php';
?>
