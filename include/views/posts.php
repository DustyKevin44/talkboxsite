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
            // Include new post form
            $pageTitle = 'Posts';
            include __DIR__ . '/posts_new.php';
            
            // Include posts list
            include __DIR__ . '/posts_list.php';
            ?>
        </div>
    </div>
</section>
