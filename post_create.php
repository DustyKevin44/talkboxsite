<?php
/**
 * Post creation API endpoint
 * 
 * Handles AJAX requests to create new posts with optional image upload.
 * Returns JSON response with success status and post data.
 * Only accepts POST requests from logged-in users.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Set JSON response header
header('Content-Type: application/json');

// Require login and POST request
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

// Get user ID
$userId = getCurrentUserId();

// Get message
$message = trim($_POST['message'] ?? '');

// Validate message
if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
    exit;
}

if (strlen($message) > 500) {
    echo json_encode(['success' => false, 'error' => 'Message cannot exceed 500 characters']);
    exit;
}

// Handle image upload
$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    
    // Validate file type
    $mimeType = mime_content_type($file['tmp_name']);
    if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
        echo json_encode(['success' => false, 'error' => 'Only JPEG and PNG images are allowed']);
        exit;
    }
    
    // Validate file size (2MB max)
    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'error' => 'Image must be smaller than 2MB']);
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = __DIR__ . '/img/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $uploadPath = $uploadDir . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
        exit;
    }
    
    // Store relative path for database
    $imagePath = 'img/uploads/' . $filename;
}

// Create post in database
$result = createPost($pdo, $userId, $message, $imagePath);

if (!$result['success']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $result['message']]);
    exit;
}

// Get the created post with user information
$postId = $result['post_id'];
$post = getPostById($pdo, $postId);

if (!$post) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to retrieve created post']);
    exit;
}

// Return success response with post data
echo json_encode([
    'success' => true,
    'message' => 'Post created successfully',
    'post' => $post
]);
exit;
?>
