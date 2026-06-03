<?php
/**
 * Logout process page
 * 
 * Destroys the user session and redirects to home page.
 */

require_once 'include/bootstrap.php';

// Destroy session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit;
?>
