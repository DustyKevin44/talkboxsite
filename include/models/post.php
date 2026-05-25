<?php
/**
 * Post model - handles post/comment-related database operations
 * 
 * This file contains functions for creating posts, retrieving posts,
 * searching posts, and other post-related operations.
 */

/**
 * Create a new post
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user ID creating the post
 * @param string $message The post message/text
 * @param string|null $imagePath Optional path to uploaded image
 * @return array ['success' => bool, 'message' => string, 'post_id' => int|null]
 */
function createPost($pdo, $userId, $message, $imagePath = null) {
    // Validate message
    $message = trim($message);
    if (empty($message)) {
        return ['success' => false, 'message' => 'Message cannot be empty'];
    }
    
    if (strlen($message) > 500) {
        return ['success' => false, 'message' => 'Message cannot exceed 500 characters'];
    }
    
    // Insert post into database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO posts (user_id, message, image_path)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $message, $imagePath]);
        
        $postId = $pdo->lastInsertId();
        return ['success' => true, 'message' => 'Post created successfully', 'post_id' => $postId];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to create post: ' . $e->getMessage()];
    }
}

/**
 * Get all posts with user information, ordered by newest first
 * 
 * @param PDO $pdo Database connection
 * @return array Array of posts with user data
 */
function getAllPosts($pdo) {
    $stmt = $pdo->prepare("
        SELECT p.id, p.user_id, p.message, p.image_path, p.created_at, 
               u.username
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get a single post with user information
 * 
 * @param PDO $pdo Database connection
 * @param int $postId The post ID
 * @return array|null Post data or null if not found
 */
function getPostById($pdo, $postId) {
    $stmt = $pdo->prepare("
        SELECT p.id, p.user_id, p.message, p.image_path, p.created_at, 
               u.username
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.id = ?
    ");
    $stmt->execute([$postId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Search posts by message content
 * 
 * @param PDO $pdo Database connection
 * @param string $searchTerm The search term
 * @return array Array of matching posts
 */
function searchPosts($pdo, $searchTerm) {
    $searchTerm = '%' . $searchTerm . '%';
    $stmt = $pdo->prepare("
        SELECT p.id, p.user_id, p.message, p.image_path, p.created_at, 
               u.username
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.message LIKE ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get posts by specific user
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user ID
 * @return array Array of user's posts
 */
function getPostsByUser($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT p.id, p.user_id, p.message, p.image_path, p.created_at, 
               u.username
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Delete a post (for admin or post owner)
 * 
 * @param PDO $pdo Database connection
 * @param int $postId The post ID
 * @param int $userId The user attempting to delete (for authorization)
 * @return array ['success' => bool, 'message' => string]
 */
function deletePost($pdo, $postId, $userId) {
    // Get post to verify ownership
    $stmt = $pdo->prepare("SELECT user_id, image_path FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        return ['success' => false, 'message' => 'Post not found'];
    }
    
    if ($post['user_id'] !== $userId) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }
    
    // Delete associated image if exists
    if ($post['image_path'] && file_exists($post['image_path'])) {
        unlink($post['image_path']);
    }
    
    // Delete post
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        return ['success' => true, 'message' => 'Post deleted successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to delete post: ' . $e->getMessage()];
    }
}

/**
 * Format timestamp for display
 * 
 * @param string $datetime The datetime string from database
 * @return string Formatted time string (e.g., "2 hours ago")
 */
function formatTimeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y', $time);
    }
}
?>
