<?php
/**
 * Home/Index page
 * 
 * Landing page for TalkBox. If user is logged in, redirects to posts.php.
 * Otherwise displays welcome message and links to login/register.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Redirect logged-in users to posts page
if (isLoggedIn()) {
    header('Location: posts.php');
    exit;
}

$pageTitle = 'Welcome to TalkBox';
include __DIR__ . '/include/views/header.php';
?>

<section class="welcome-section">
    <div class="welcome-container">
        <div class="welcome-content">
            <h2>Welcome to TalkBox</h2>
            <p>A simple, modern comment application where you can share your thoughts with others.</p>
            
            <div class="welcome-features">
                <h3>Features</h3>
                <ul>
                    <li>Create an account and post comments</li>
                    <li>Share images with your posts</li>
                    <li>Update your profile information</li>
                    <li>Search through all posts</li>
                    <li>Real-time comment updates</li>
                </ul>
            </div>
            
            <div class="welcome-cta">
                <a href="register.php" class="btn btn-primary">Create Account</a>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/include/views/footer.php'; ?>
