<?php
/**
 * Posts/Comments page
 * 
 * Main page where users can view all posts and search them.
 * Logged-in users can also create new posts.
 */

require_once 'include/bootstrap.php';

// Get all posts with user information
$posts = getAllPosts($pdo);

$pageTitle = 'Posts - TalkBox';
include 'include/views/_header.php';
include 'include/views/_posts.php';
include 'include/views/_footer.php';
?>
