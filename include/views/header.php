<?php
/**
 * Header view - navigation and page header
 * 
 * Displayed at the top of every page. Shows navigation links and branding.
 * Dynamically displays different links based on authentication status.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escapeHtml($pageTitle ?? 'TalkBox'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="header-logo">
                <a href="<?php echo isLoggedIn() ? 'posts.php' : 'index.php'; ?>">TalkBox</a>
            </h1>
            <nav class="header-nav">
                <?php if (isLoggedIn()): ?>
                    <a href="posts.php" class="nav-link">Posts</a>
                    <a href="profile.php" class="nav-link">Profile</a>
                    <a href="logout_process.php" class="nav-link nav-link-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                    <a href="register.php" class="nav-link">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
