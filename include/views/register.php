<?php
/**
 * Register form view
 * 
 * Displays the registration form with email, username, and password fields.
 * Included in register.php
 */

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Create Account</h2>
        
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
        
        <form id="registerForm" method="POST" action="register_process.php" class="form">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    required
                    placeholder="your@email.com"
                >
                <span class="form-error" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-input" 
                    required
                    placeholder="john_doe"
                    pattern="[a-zA-Z0-9_]{3,20}"
                    title="3-20 characters, alphanumeric and underscores only"
                >
                <span class="form-error" id="usernameError"></span>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    required
                    placeholder="••••••••"
                    minlength="8"
                    title="Minimum 8 characters"
                >
                <span class="form-error" id="passwordError"></span>
            </div>
            
            <div class="form-group">
                <label for="passwordConfirm" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    id="passwordConfirm" 
                    name="passwordConfirm" 
                    class="form-input" 
                    required
                    placeholder="••••••••"
                >
                <span class="form-error" id="passwordConfirmError"></span>
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        
        <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>
