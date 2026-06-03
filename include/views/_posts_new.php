<?php
/**
 * New post form view
 * 
 * Displays the form for creating a new post with message and optional image.
 * Uses AJAX for submission to prevent page reload.
 * Included in _posts.php
 */
?>

<section class="post-creation-section">
    <div class="post-creation-box">
        <h2>Create a Post</h2>
        
        <form id="newPostForm" class="form post-form">
            <div class="form-group">
                <label for="message" class="form-label">Message</label>
                <textarea 
                    id="message" 
                    name="message" 
                    class="form-textarea" 
                    placeholder="What's on your mind? (max 500 characters)"
                    required
                    maxlength="500"
                    rows="4"
                ></textarea>
                <div class="char-counter">
                    <span id="charCount">0</span>/500
                </div>
                <span class="form-error" id="messageError"></span>
            </div>
            
            <div class="form-group">
                <label for="image" class="form-label">Image (optional)</label>
                <div class="file-input-wrapper">
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/jpeg,image/png"
                        class="form-file-input"
                    >
                    <label for="image" class="file-input-label">Choose Image</label>
                    <span id="fileName" class="file-name"></span>
                </div>
                <small>JPEG or PNG only, max 64MB</small>
                <span class="form-error" id="imageError"></span>
            </div>
            
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
        
        <div id="postMessage" class="post-message-container"></div>
    </div>
</section>
