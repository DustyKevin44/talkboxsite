<?php
/**
 * Posts/Comments page
 * 
 * Main application page where logged-in users can view all posts,
 * create new posts, and search posts. Requires authentication.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Require login
requireLogin();

// Get all posts with user information
$posts = getAllPosts($pdo);

$pageTitle = 'Posts - TalkBox';
include __DIR__ . '/include/views/header.php';
include __DIR__ . '/include/views/posts.php';
include __DIR__ . '/include/views/footer.php';
?>
