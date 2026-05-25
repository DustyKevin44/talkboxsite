<?php
/**
 * User model - handles user-related database operations
 * 
 * This file contains functions for user registration, login, profile updates,
 * and other user-related operations. All database operations use prepared statements.
 */

/**
 * Register a new user
 * 
 * @param PDO $pdo Database connection
 * @param string $email User's email address
 * @param string $username User's username
 * @param string $password User's password (plain text - will be hashed)
 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
 */
function registerUser($pdo, $email, $username, $password) {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    // Validate username (3-20 characters, alphanumeric and underscores only)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        return ['success' => false, 'message' => 'Username must be 3-20 characters, alphanumeric and underscores only'];
    }
    
    // Validate password (minimum 8 characters)
    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Username already taken'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user into database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (email, username, password)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$email, $username, $hashedPassword]);
        
        $userId = $pdo->lastInsertId();
        return ['success' => true, 'message' => 'Registration successful', 'user_id' => $userId];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
}

/**
 * Authenticate user login
 * 
 * @param PDO $pdo Database connection
 * @param string $email User's email
 * @param string $password User's password (plain text)
 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
 */
function loginUser($pdo, $email, $password) {
    // Validate inputs
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    // Fetch user from database
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user exists and password is correct
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    return ['success' => true, 'message' => 'Login successful', 'user_id' => $user['id']];
}

/**
 * Get user by ID
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user ID to fetch
 * @return array|null User data or null if not found
 */
function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT id, email, username, profile_image, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update user's username
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user ID
 * @param string $newUsername The new username
 * @return array ['success' => bool, 'message' => string]
 */
function updateUsername($pdo, $userId, $newUsername) {
    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $newUsername)) {
        return ['success' => false, 'message' => 'Username must be 3-20 characters, alphanumeric and underscores only'];
    }
    
    // Check if username already taken
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$newUsername, $userId]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Username already taken'];
    }
    
    // Update username
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$newUsername, $userId]);
        return ['success' => true, 'message' => 'Username updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Update failed: ' . $e->getMessage()];
    }
}

/**
 * Update user's password
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user ID
 * @param string $currentPassword The current password (for verification)
 * @param string $newPassword The new password
 * @return array ['success' => bool, 'message' => string]
 */
function updatePassword($pdo, $userId, $currentPassword, $newPassword) {
    // Validate new password
    if (strlen($newPassword) < 8) {
        return ['success' => false, 'message' => 'New password must be at least 8 characters'];
    }
    
    // Fetch user
    $user = getUserById($pdo, $userId);
    if (!$user) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    // Get the full user record including password hash
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verify current password
    if (!password_verify($currentPassword, $userRecord['password'])) {
        return ['success' => false, 'message' => 'Current password is incorrect'];
    }
    
    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    
    // Update password
    try {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $userId]);
        return ['success' => true, 'message' => 'Password updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Update failed: ' . $e->getMessage()];
    }
}
?>
