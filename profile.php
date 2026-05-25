<?php
/**
 * Profile page
 * 
 * Allows logged-in users to view and edit their profile information including
 * username and password. Requires authentication.
 */

require_once __DIR__ . '/include/bootstrap.php';

// Require login
requireLogin();

$userId = getCurrentUserId();
$user = getUserById($pdo, $userId);

if (!$user) {
    header('Location: posts.php');
    exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

$pageTitle = 'Profile - TalkBox';
include __DIR__ . '/include/views/header.php';
?>

<section class="profile-section">
    <div class="profile-container">
        <h2>My Profile</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo escapeHtml($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo escapeHtml($success); ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-content">
            <div class="profile-info">
                <div class="profile-field">
                    <label>Email</label>
                    <p class="profile-value"><?php echo escapeHtml($user['email']); ?></p>
                </div>
                
                <div class="profile-field">
                    <label>Username</label>
                    <p class="profile-value"><?php echo escapeHtml($user['username']); ?></p>
                </div>
                
                <div class="profile-field">
                    <label>Member Since</label>
                    <p class="profile-value">
                        <?php echo date('F d, Y', strtotime($user['created_at'])); ?>
                    </p>
                </div>
            </div>
            
            <div class="profile-forms">
                <!-- Change Username Form -->
                <form id="changeUsernameForm" method="POST" action="profile_process.php" class="form profile-form">
                    <input type="hidden" name="action" value="update_username">
                    
                    <h3>Change Username</h3>
                    
                    <div class="form-group">
                        <label for="newUsername" class="form-label">New Username</label>
                        <input 
                            type="text" 
                            id="newUsername" 
                            name="newUsername" 
                            class="form-input" 
                            required
                            pattern="[a-zA-Z0-9_]{3,20}"
                            placeholder="john_doe"
                            title="3-20 characters, alphanumeric and underscores only"
                        >
                        <span class="form-error" id="newUsernameError"></span>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Username</button>
                </form>
                
                <!-- Change Password Form -->
                <form id="changePasswordForm" method="POST" action="profile_process.php" class="form profile-form">
                    <input type="hidden" name="action" value="update_password">
                    
                    <h3>Change Password</h3>
                    
                    <div class="form-group">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input 
                            type="password" 
                            id="currentPassword" 
                            name="currentPassword" 
                            class="form-input" 
                            required
                            placeholder="••••••••"
                        >
                        <span class="form-error" id="currentPasswordError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input 
                            type="password" 
                            id="newPassword" 
                            name="newPassword" 
                            class="form-input" 
                            required
                            placeholder="••••••••"
                            minlength="8"
                            title="Minimum 8 characters"
                        >
                        <span class="form-error" id="newPasswordError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPasswordConfirm" class="form-label">Confirm New Password</label>
                        <input 
                            type="password" 
                            id="newPasswordConfirm" 
                            name="newPasswordConfirm" 
                            class="form-input" 
                            required
                            placeholder="••••••••"
                        >
                        <span class="form-error" id="newPasswordConfirmError"></span>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/include/views/footer.php'; ?>
