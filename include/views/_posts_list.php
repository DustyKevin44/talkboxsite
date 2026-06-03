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
            <article class="post-card<?php echo isLoggedIn() && (int)$post['user_id'] === getCurrentUserId() ? ' own-post' : ''; ?>" data-post-id="<?php echo (int)$post['id']; ?>">
                <div class="post-header">
                    <h3 class="post-author"><?php echo escapeHtml($post['username']); ?></h3>
                    <div class="post-header-actions">
                        <time class="post-time" title="<?php echo escapeHtml($post['created_at']); ?>">
                            <?php echo formatTimeAgo($post['created_at']); ?>
                        </time>
                        <?php if (isLoggedIn() && (int)$post['user_id'] === getCurrentUserId()): ?>
                            <button type="button" class="post-delete-btn" data-delete-post="<?php echo (int)$post['id']; ?>" aria-label="Delete post">Delete</button>
                        <?php endif; ?>
                    </div>
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
