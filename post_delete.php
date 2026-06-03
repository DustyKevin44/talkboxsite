<?php
/**
 * Post deletion API endpoint
 *
 * Handles AJAX requests to delete posts owned by the current user.
 * Returns JSON response with success status.
 */

require_once 'include/bootstrap.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$postId = (int)($_POST['post_id'] ?? 0);
if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid post id']);
    exit;
}

$result = deletePost($pdo, $postId, getCurrentUserId());

if (!$result['success']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => $result['message']]);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
exit;
