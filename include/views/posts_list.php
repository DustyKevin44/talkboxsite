<?php
/**
 * Posts list view
 * 
 * Displays all posts/comments with user information, timestamps, and optional images.
 * Used in posts.php. Does not include the form to create new posts.
 */
?>

<div id="postsList" class="posts-list">
    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <p>No posts yet. Be the first to post!</p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <div class="post-header">
                    <h3 class="post-author"><?php echo escapeHtml($post['username']); ?></h3>
                    <time class="post-time" title="<?php echo escapeHtml($post['created_at']); ?>">
                        <?php echo formatTimeAgo($post['created_at']); ?>
                    </time>
                </div>
                
                <div class="post-content">
                    <p class="post-message"><?php echo nl2br(escapeHtml($post['message'])); ?></p>
                    
                    <?php if ($post['image_path'] && file_exists($post['image_path'])): ?>
                        <div class="post-image-container">
                            <img 
                                src="<?php echo escapeHtml($post['image_path']); ?>" 
                                alt="Post image" 
                                class="post-image"
                            >
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
