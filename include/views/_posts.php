<?php
/**
 * Posts page main view
 * 
 * Main posts/comments page layout including search, post creation form, and posts list.
 * Included in posts.php after header.
 */
?>

<section class="posts-page">
    <div class="posts-container">
        <div class="posts-sidebar">
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Search posts..."
                >
            </div>
        </div>
        
        <div class="posts-main">
            <?php
            // Show the create-post form only for logged-in users
            if (isLoggedIn()) {
                include '_posts_new.php';
            }

            // Include posts list
            include '_posts_list.php';
            ?>
        </div>
    </div>
</section>
