<?php
/**
 * Login form view
 * 
 * Displays the login form with email and password fields.
 * Included in login.php
 */

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Login to TalkBox</h2>
        
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
        
        <form id="loginForm" method="POST" action="login_process.php" class="form">
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
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    required
                    placeholder="••••••••"
                >
                <span class="form-error" id="passwordError"></span>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <p class="auth-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
