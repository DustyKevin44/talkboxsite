/**
 * TalkBox - Main JavaScript File
 * 
 * Handles client-side form validation, AJAX post submission,
 * real-time character counter, and post search functionality.
 */

/**
 * Display error message in form
 * 
 * @param {string} fieldId - The ID of the error display element
 * @param {string} message - The error message to display
 */
function showError(fieldId, message) {
    const errorEl = document.getElementById(fieldId);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add('show');
    }
}

/**
 * Clear error message from form
 * 
 * @param {string} fieldId - The ID of the error display element
 */
function clearError(fieldId) {
    const errorEl = document.getElementById(fieldId);
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('show');
    }
}

/**
 * Validate email format
 * 
 * @param {string} email - Email to validate
 * @returns {boolean} True if valid, false otherwise
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate username format
 * 
 * @param {string} username - Username to validate
 * @returns {object} {valid: boolean, message: string}
 */
function isValidUsername(username) {
    if (username.length < 3 || username.length > 20) {
        return { valid: false, message: 'Username must be 3-20 characters' };
    }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        return { valid: false, message: 'Username must contain only letters, numbers, and underscores' };
    }
    return { valid: true, message: '' };
}

/**
 * Validate password format
 * 
 * @param {string} password - Password to validate
 * @returns {object} {valid: boolean, message: string}
 */
function isValidPassword(password) {
    if (password.length < 8) {
        return { valid: false, message: 'Password must be at least 8 characters' };
    }
    return { valid: true, message: '' };
}

/**
 * Display success alert message
 * 
 * @param {string} message - The success message
 * @param {HTMLElement} container - Container to insert message into
 */
function showSuccessAlert(message, container) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success';
    alert.textContent = message;
    
    if (container.firstChild) {
        container.insertBefore(alert, container.firstChild);
    } else {
        container.appendChild(alert);
    }
    
    setTimeout(() => alert.remove(), 5000);
}

/**
 * Display error alert message
 * 
 * @param {string} message - The error message
 * @param {HTMLElement} container - Container to insert message into
 */
function showErrorAlert(message, container) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-error';
    alert.textContent = message;
    
    if (container.firstChild) {
        container.insertBefore(alert, container.firstChild);
    } else {
        container.appendChild(alert);
    }
    
    setTimeout(() => alert.remove(), 5000);
}

/**
 * Format timestamp for display (relative time)
 * 
 * @param {string} datetime - ISO 8601 datetime string
 * @returns {string} Formatted time (e.g., "2 hours ago")
 */
function formatTimeAgo(datetime) {
    const time = new Date(datetime).getTime();
    const now = new Date().getTime();
    const diff = Math.floor((now - time) / 1000);
    
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + ' minute' + (Math.floor(diff / 60) > 1 ? 's' : '') + ' ago';
    if (diff < 86400) return Math.floor(diff / 3600) + ' hour' + (Math.floor(diff / 3600) > 1 ? 's' : '') + ' ago';
    if (diff < 604800) return Math.floor(diff / 86400) + ' day' + (Math.floor(diff / 86400) > 1 ? 's' : '') + ' ago';
    
    const date = new Date(datetime);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

/**
 * Create and insert a new post card into the DOM
 * 
 * @param {object} post - Post data from server
 */
function insertNewPost(post) {
    const postsList = document.getElementById('postsList');
    if (!postsList) return;
    
    const emptyState = postsList.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    const postCard = document.createElement('article');
    postCard.className = 'post-card new-post';
    
    let imageHtml = '';
    if (post.image_path) {
        imageHtml = `<div class="post-image-container"><img src="${escapeHtml(post.image_path)}" alt="Post image" class="post-image"></div>`;
    }
    
    postCard.innerHTML = `
        <div class="post-header">
            <h3 class="post-author">${escapeHtml(post.username)}</h3>
            <time class="post-time" title="${escapeHtml(post.created_at)}">${formatTimeAgo(post.created_at)}</time>
        </div>
        <div class="post-content">
            <p class="post-message">${escapeHtml(post.message).replace(/\n/g, '<br>')}</p>
            ${imageHtml}
        </div>
    `;
    
    postsList.insertBefore(postCard, postsList.firstChild);
    
    // Remove new-post styling after animation
    setTimeout(() => postCard.classList.remove('new-post'), 3000);
}

/**
 * Escape HTML special characters to prevent XSS
 * 
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ========== LOGIN FORM VALIDATION ==========

document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate email
            const emailInput = document.getElementById('email');
            if (emailInput) {
                clearError('emailError');
                const email = emailInput.value.trim();
                if (!email) {
                    showError('emailError', 'Email is required');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    showError('emailError', 'Please enter a valid email');
                    isValid = false;
                }
            }
            
            // Validate password
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                clearError('passwordError');
                const password = passwordInput.value;
                if (!password) {
                    showError('passwordError', 'Password is required');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // ========== REGISTER FORM VALIDATION ==========
    
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate email
            const emailInput = document.getElementById('email');
            if (emailInput) {
                clearError('emailError');
                const email = emailInput.value.trim();
                if (!email) {
                    showError('emailError', 'Email is required');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    showError('emailError', 'Please enter a valid email');
                    isValid = false;
                }
            }
            
            // Validate username
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                clearError('usernameError');
                const username = usernameInput.value.trim();
                if (!username) {
                    showError('usernameError', 'Username is required');
                    isValid = false;
                } else {
                    const usernameValidation = isValidUsername(username);
                    if (!usernameValidation.valid) {
                        showError('usernameError', usernameValidation.message);
                        isValid = false;
                    }
                }
            }
            
            // Validate password
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                clearError('passwordError');
                const password = passwordInput.value;
                if (!password) {
                    showError('passwordError', 'Password is required');
                    isValid = false;
                } else {
                    const passwordValidation = isValidPassword(password);
                    if (!passwordValidation.valid) {
                        showError('passwordError', passwordValidation.message);
                        isValid = false;
                    }
                }
            }
            
            // Validate password confirmation
            const passwordConfirmInput = document.getElementById('passwordConfirm');
            if (passwordConfirmInput) {
                clearError('passwordConfirmError');
                const passwordConfirm = passwordConfirmInput.value;
                const password = document.getElementById('password').value;
                if (!passwordConfirm) {
                    showError('passwordConfirmError', 'Please confirm password');
                    isValid = false;
                } else if (password !== passwordConfirm) {
                    showError('passwordConfirmError', 'Passwords do not match');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // ========== PROFILE FORM VALIDATION ==========
    
    const changeUsernameForm = document.getElementById('changeUsernameForm');
    if (changeUsernameForm) {
        changeUsernameForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            const newUsernameInput = document.getElementById('newUsername');
            if (newUsernameInput) {
                clearError('newUsernameError');
                const username = newUsernameInput.value.trim();
                if (!username) {
                    showError('newUsernameError', 'Username is required');
                    isValid = false;
                } else {
                    const usernameValidation = isValidUsername(username);
                    if (!usernameValidation.valid) {
                        showError('newUsernameError', usernameValidation.message);
                        isValid = false;
                    }
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            const currentPasswordInput = document.getElementById('currentPassword');
            if (currentPasswordInput) {
                clearError('currentPasswordError');
                if (!currentPasswordInput.value) {
                    showError('currentPasswordError', 'Current password is required');
                    isValid = false;
                }
            }
            
            const newPasswordInput = document.getElementById('newPassword');
            if (newPasswordInput) {
                clearError('newPasswordError');
                const password = newPasswordInput.value;
                if (!password) {
                    showError('newPasswordError', 'New password is required');
                    isValid = false;
                } else {
                    const passwordValidation = isValidPassword(password);
                    if (!passwordValidation.valid) {
                        showError('newPasswordError', passwordValidation.message);
                        isValid = false;
                    }
                }
            }
            
            const newPasswordConfirmInput = document.getElementById('newPasswordConfirm');
            if (newPasswordConfirmInput) {
                clearError('newPasswordConfirmError');
                const passwordConfirm = newPasswordConfirmInput.value;
                const password = document.getElementById('newPassword').value;
                if (!passwordConfirm) {
                    showError('newPasswordConfirmError', 'Please confirm new password');
                    isValid = false;
                } else if (password !== passwordConfirm) {
                    showError('newPasswordConfirmError', 'Passwords do not match');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // ========== CHARACTER COUNTER ==========
    
    const messageTextarea = document.getElementById('message');
    if (messageTextarea) {
        const charCount = document.getElementById('charCount');
        
        messageTextarea.addEventListener('input', function() {
            if (charCount) {
                charCount.textContent = this.value.length;
            }
        });
    }
    
    // ========== FILE INPUT DISPLAY NAME ==========
    
    const fileInput = document.getElementById('image');
    if (fileInput) {
        const fileName = document.getElementById('fileName');
        
        fileInput.addEventListener('change', function() {
            if (fileName) {
                if (this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                } else {
                    fileName.textContent = '';
                }
            }
        });
    }
    
    // ========== NEW POST FORM AJAX SUBMISSION ==========
    
    const newPostForm = document.getElementById('newPostForm');
    if (newPostForm) {
        newPostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const messageInput = document.getElementById('message');
            const imageInput = document.getElementById('image');
            const postMessage = document.getElementById('postMessage');
            
            // Validate message
            clearError('messageError');
            if (!messageInput.value.trim()) {
                showError('messageError', 'Message cannot be empty');
                isValid = false;
            }
            
            if (messageInput.value.length > 500) {
                showError('messageError', 'Message cannot exceed 500 characters');
                isValid = false;
            }
            
            // Validate image if present
            clearError('imageError');
            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                
                // Check file type
                if (!['image/jpeg', 'image/png'].includes(file.type)) {
                    showError('imageError', 'Only JPEG and PNG images are allowed');
                    isValid = false;
                }
                
                // Check file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showError('imageError', 'Image must be smaller than 2MB');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                return;
            }
            
            // Create FormData for file upload
            const formData = new FormData();
            formData.append('message', messageInput.value.trim());
            
            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Show loading state
            const submitButton = newPostForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Posting...';
            submitButton.disabled = true;
            
            // Send AJAX request
            fetch('post_create.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form
                    newPostForm.reset();
                    if (charCount) charCount.textContent = '0';
                    if (fileName) fileName.textContent = '';
                    
                    // Insert new post into DOM
                    insertNewPost(data.post);
                    
                    // Show success message
                    if (postMessage) {
                        showSuccessAlert('Post created successfully!', postMessage);
                    }
                } else {
                    if (postMessage) {
                        showErrorAlert(data.error || 'Failed to create post', postMessage);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (postMessage) {
                    showErrorAlert('An error occurred while posting', postMessage);
                }
            })
            .finally(() => {
                // Restore button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    }
    
    // ========== POST SEARCH/FILTER ==========
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const postCards = document.querySelectorAll('.post-card');
            let visibleCount = 0;
            
            postCards.forEach(card => {
                const postMessage = card.querySelector('.post-message');
                const postAuthor = card.querySelector('.post-author');
                
                if (postMessage && postAuthor) {
                    const messageText = postMessage.textContent.toLowerCase();
                    const authorText = postAuthor.textContent.toLowerCase();
                    
                    if (messageText.includes(searchTerm) || authorText.includes(searchTerm)) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Show/hide empty state
            const postsList = document.getElementById('postsList');
            if (postsList) {
                let emptyState = postsList.querySelector('.empty-state');
                if (visibleCount === 0 && searchTerm) {
                    if (!emptyState) {
                        emptyState = document.createElement('div');
                        emptyState.className = 'empty-state';
                        emptyState.textContent = 'No posts found matching your search.';
                        postsList.appendChild(emptyState);
                    }
                    if (emptyState) emptyState.style.display = '';
                } else if (emptyState && searchTerm) {
                    emptyState.style.display = 'none';
                }
            }
        });
    }
});
