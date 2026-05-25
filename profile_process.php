<?php
/**
 * Profile update process page
 * 
 * Handles profile update requests including username changes and password changes.
 * Validates input server-side and redirects back to profile.php with success/error message.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Require login and POST request
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$userId = getCurrentUserId();
$action = $_POST['action'] ?? '';

// Handle username update
if ($action === 'update_username') {
    $newUsername = trim($_POST['newUsername'] ?? '');
    
    if (empty($newUsername)) {
        header('Location: profile.php?error=' . urlencode('Username cannot be empty'));
        exit;
    }
    
    $result = updateUsername($pdo, $userId, $newUsername);
    
    if ($result['success']) {
        header('Location: profile.php?success=' . urlencode('Username updated successfully'));
    } else {
        header('Location: profile.php?error=' . urlencode($result['message']));
    }
    exit;
}

// Handle password update
if ($action === 'update_password') {
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $newPasswordConfirm = $_POST['newPasswordConfirm'] ?? '';
    
    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirm)) {
        header('Location: profile.php?error=' . urlencode('All password fields are required'));
        exit;
    }
    
    if ($newPassword !== $newPasswordConfirm) {
        header('Location: profile.php?error=' . urlencode('New passwords do not match'));
        exit;
    }
    
    $result = updatePassword($pdo, $userId, $currentPassword, $newPassword);
    
    if ($result['success']) {
        header('Location: profile.php?success=' . urlencode('Password updated successfully'));
    } else {
        header('Location: profile.php?error=' . urlencode($result['message']));
    }
    exit;
}

// Invalid action
header('Location: profile.php?error=' . urlencode('Invalid action'));
exit;
?>
