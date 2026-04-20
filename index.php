<?php
// index.php — Portfolio System Home Launcher
session_start();

// If already logged in as admin, redirect to panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /MyWebsite/panel.php', true, 302);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Bryan Darryl Carpio Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional styles for landing page */
        .landing-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-card {
            background: 
                radial-gradient(ellipse at 0% 0%, rgba(102, 126, 234, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 0%, rgba(118, 75, 162, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 100%, rgba(240, 84, 84, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 0% 100%, rgba(79, 172, 254, 0.1) 0%, transparent 50%),
                var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 40px;
            max-width: 900px;
            width: 100%;
            backdrop-filter: blur(12px);
            box-shadow: 
                0 25px 60px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.1),
                inset 0 -1px 0 rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .welcome-title {
            font-size: 3rem;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            text-align: center;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 4px 30px rgba(102, 126, 234, 0.3);
            position: relative;
        }
        
        .welcome-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--gradient-2);
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        .welcome-subtitle {
            color: var(--text-secondary);
            margin-bottom: 40px;
            font-size: 1.1rem;
            text-align: center;
        }
        
        /* Horizontal Layout */
        .options-container {
            display: flex;
            gap: 30px;
            align-items: stretch;
        }
        
        /* Public View Section */
        .public-section {
            flex: 1;
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .public-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border-color: var(--highlight);
        }
        
        .public-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .public-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
        }
        
        .public-description {
            color: var(--text-secondary);
            margin-bottom: 25px;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .btn-dashboard {
            background: var(--gradient-2);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 700;
            display: inline-block;
            width: 100%;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            letter-spacing: 1px;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(240, 84, 84, 0.4);
        }
        
        /* Vertical Divider */
        .vertical-divider {
            width: 1px;
            background: var(--border);
            margin: 20px 0;
        }
        
        /* Admin Section */
        .admin-section {
            flex: 1;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        
        .admin-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border-color: var(--highlight);
        }
        
        .admin-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .admin-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .field {
            margin-bottom: 20px;
        }
        
        .field label {
            display: block;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            font-size: 1.1rem;
            color: var(--text-secondary);
        }
        
        .field input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .field input:focus {
            outline: none;
            border-color: var(--highlight);
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 0 3px rgba(240, 84, 84, 0.2);
        }
        
        .field input::placeholder {
            color: rgba(184,193,236,0.4);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--gradient-1);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 5px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
        }
        
        .error-message {
            background: rgba(240,84,84,0.2);
            border: 1px solid var(--highlight);
            color: var(--highlight);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .password-requirements {
            background: rgba(255, 193, 7, 0.15);
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 8px;
            margin-top: 12px;
            font-size: 0.85rem;
            display: none;
        }

        .password-requirements.show {
            display: block;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .password-requirements h4 {
            margin: 0 0 6px 0;
            font-size: 0.9rem;
            color: #856404;
            width: 100%;
        }

        .req-item {
            display: flex;
            align-items: center;
            margin: 2px 0;
            font-size: 0.8rem;
            width: 100%;
        }

        .req-item.met {
            color: #28a745;
        }

        .req-item.unmet {
            color: #dc3545;
        }

        .req-icon {
            display: inline-block;
            width: 16px;
            text-align: center;
            margin-right: 6px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .admin-footer {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
            font-size: 0.8rem;
            border-top: 1px solid var(--border);
            padding-top: 15px;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            transition: all 0.3s;
            opacity: 0.6;
        }
        
        .password-toggle:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        /* Entrance Animations - Fast & Snappy */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome-card {
            animation: fadeInUp 0.3s ease-out;
        }
        
        .public-section,
        .admin-section {
            animation: fadeInUp 0.25s ease-out;
        }
        
        /* Button Loading State */
        .btn-login.loading,
        .btn-dashboard.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-login.loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-left: 10px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .options-container {
                flex-direction: column;
            }
            
            .vertical-divider {
                display: none;
            }
            
            .welcome-card {
                padding: 30px 20px;
            }
            
            .welcome-title {
                font-size: 1.8rem;
            }
            
            .public-section,
            .admin-section {
                padding: 25px 20px;
                border-radius: 15px;
            }
            
            .public-icon,
            .admin-icon {
                font-size: 2.2rem;
                margin-bottom: 12px;
            }
            
            .public-title,
            .admin-title {
                font-size: 1.2rem;
            }
            
            .public-description {
                font-size: 0.9rem;
                margin-bottom: 20px;
            }
            
            .btn-dashboard,
            .btn-login {
                font-size: 0.95rem;
                padding: 14px 20px;
                min-height: 44px;
            }
            
            .field input {
                padding: 14px 15px 14px 45px;
                font-size: 16px;
            }
            
            .input-icon {
                left: 14px;
            }
            
            .field label {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            .landing-container {
                padding: 15px;
            }
            
            .welcome-card {
                padding: 25px 15px;
                border-radius: 20px;
            }
            
            .welcome-title {
                font-size: 1.5rem;
                letter-spacing: 1px;
            }
            
            .welcome-quote {
                font-size: 0.85rem !important;
                margin-bottom: 20px !important;
            }
            
            .options-container {
                gap: 15px;
                flex-direction: column;
            }
            
            .public-section,
            .admin-section {
                padding: 20px 15px;
                border-radius: 12px;
            }
            
            .public-icon,
            .admin-icon {
                font-size: 2rem;
                margin-bottom: 10px;
            }
            
            .public-title,
            .admin-title {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            
            .public-description {
                font-size: 0.85rem;
                margin-bottom: 15px;
            }
            
            .btn-dashboard,
            .btn-login {
                font-size: 0.9rem;
                padding: 13px 18px;
                min-height: 44px;
                letter-spacing: 0.5px;
                width: 100%;
            }
            
            .field {
                margin-bottom: 16px;
            }
            
            .field label {
                font-size: 0.8rem;
                margin-bottom: 6px;
            }
            
            .field input {
                padding: 13px 14px 13px 42px;
                font-size: 16px;
                border-radius: 10px;
            }
            
            .input-icon {
                left: 12px;
                font-size: 1rem;
            }
            
            .password-toggle {
                font-size: 0.9rem;
            }
            
            .admin-footer {
                font-size: 0.8rem;
                margin-top: 12px;
            }
            
            .error-message {
                font-size: 0.85rem;
                padding: 10px;
                border-radius: 8px;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 320px) {
            .landing-container {
                padding: 10px;
            }
            
            .welcome-card {
                padding: 20px 12px;
                border-radius: 15px;
            }
            
            .welcome-title {
                font-size: 1.3rem;
            }
            
            .welcome-quote {
                font-size: 0.75rem !important;
            }
            
            .public-icon,
            .admin-icon {
                font-size: 1.8rem;
            }
            
            .public-title,
            .admin-title {
                font-size: 1rem;
            }
            
            .public-description {
                font-size: 0.8rem;
            }
            
            .btn-dashboard,
            .btn-login {
                font-size: 0.85rem;
                padding: 12px 16px;
            }
        }

    </style>
</head>
<body>
    <div class="bg-element"></div>
    <div class="bg-element"></div>
    <div class="bg-element"></div>
    
    <div class="landing-container">
        <div class="welcome-card">
            <h1 class="welcome-title" style="margin-top: 20px;">Daryl Dev Hub</h1>
            <p class="welcome-quote" style="text-align: center; color: var(--text-secondary); font-style: italic; font-size: 0.95rem; margin-bottom: 30px; opacity: 0.85;">"The best way to predict the future is to create it."<span style="display: block; font-size: 0.8rem; margin-top: 5px; opacity: 0.7;">— Peter Drucker</span></p>
            
            <!-- Horizontal Layout Container -->
            <div class="options-container">
                
                <!-- PUBLIC SECTION - View Dashboard -->
                <div class="public-section">
                    <div class="public-icon">&#10024;</div>
                    <h2 class="public-title">Explore My Work</h2>
                    <p class="public-description">
                        Discover my projects, skills, and journey in tech.
                    </p>
                    <a href="dashboard.php" class="btn-dashboard">
                        EXPLORE NOW
                    </a>
                </div>
                
                <!-- Vertical Divider -->
                <div class="vertical-divider"></div>
                
                <!-- ADMIN SECTION - Login Form -->
                <div class="admin-section">
                    <div class="admin-icon">🔒</div>
                    <h2 class="admin-title">Administrator Login</h2>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message">
                            ⚠️ Invalid credentials
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login.php">
                        <div class="field">
                            <label for="username">Username</label>
                            <div class="input-wrap">
                                <span class="input-icon">👤</span>
                                <input type="text" id="username" name="username" autocomplete="new-username" required>
                            </div>
                        </div>
                        
                        <div class="field">
                            <label for="password">Password</label>
                            <div class="input-wrap">
                                <span class="input-icon">🔑</span>
                                <input type="password" id="password" name="password" autocomplete="new-password" required>
                                <button type="button" class="password-toggle" id="toggleBtn" title="Show/Hide Password">
                                    👁️
                                </button>
                            </div>
                            
                            <!-- Password Requirements -->
                            <div class="password-requirements" id="passwordReqs">
                                <h4>Password Requirements:</h4>
                                <div class="req-item" id="req-length">
                                    <span class="req-icon">✗</span> At least 8 characters
                                </div>
                                <div class="req-item" id="req-uppercase">
                                    <span class="req-icon">✗</span> One uppercase letter (A-Z)
                                </div>
                                <div class="req-item" id="req-number">
                                    <span class="req-icon">✗</span> One number (0-9)
                                </div>
                                <div class="req-item" id="req-special">
                                    <span class="req-icon">✗</span> One special character (@, #, $, %, &, !, etc.)
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            LOGIN
                        </button>
                    </form>
                    
                    <div class="admin-footer">
                        Portfolio management access
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password requirements checker
        function checkPasswordRequirements(password) {
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@#$%&!._\-*()+=\[\]{};':"\\|,.<>?/`~]/.test(password);

            // Update requirement items
            updateRequirement('req-length', hasLength);
            updateRequirement('req-uppercase', hasUppercase);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);

            return hasLength && hasUppercase && hasNumber && hasSpecial;
        }

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            if (met) {
                element.classList.remove('unmet');
                element.classList.add('met');
                element.querySelector('.req-icon').textContent = '✓';
            } else {
                element.classList.remove('met');
                element.classList.add('unmet');
                element.querySelector('.req-icon').textContent = '✗';
            }
        }

        // Password input event listener
        (function() {
            const passwordInput = document.getElementById('password');
            const passwordReqs = document.getElementById('passwordReqs');
            
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        passwordReqs.classList.add('show');
                        checkPasswordRequirements(this.value);
                    } else {
                        passwordReqs.classList.remove('show');
                    }
                });
                
                passwordInput.addEventListener('focus', function() {
                    if (this.value.length > 0) {
                        passwordReqs.classList.add('show');
                    }
                });
            }
        })();

        // Password visibility toggle - Allow button clicks
        (function() {
            const toggleBtn = document.getElementById('toggleBtn');
            const passwordInput = document.getElementById('password');
            
            if (toggleBtn && passwordInput) {
                // Prevent security.js from blocking this button
                toggleBtn.addEventListener('mousedown', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
                
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleBtn.textContent = '🙈';
                        toggleBtn.title = 'Hide Password';
                    } else {
                        passwordInput.type = 'password';
                        toggleBtn.textContent = '👁️';
                        toggleBtn.title = 'Show Password';
                    }
                }, true);
            }
        })();
        
        // Aggressive autofill prevention
        window.addEventListener('load', function() {
            const usernameField = document.getElementById('username');
            const passwordField = document.getElementById('password');
            
            // Step 1: Set readonly to block autofill
            if (usernameField) usernameField.setAttribute('readonly', 'readonly');
            if (passwordField) passwordField.setAttribute('readonly', 'readonly');
            
            // Step 2: Clear immediately
            if (usernameField) usernameField.value = '';
            if (passwordField) passwordField.value = '';
            
            // Step 3: Remove readonly after 100ms to allow user input
            setTimeout(function() {
                if (usernameField) usernameField.removeAttribute('readonly');
                if (passwordField) passwordField.removeAttribute('readonly');
            }, 100);
            
            // Step 4: Monitor for any unexpected values and clear them
            if (usernameField) {
                usernameField.addEventListener('input', function() {
                    // If field gets auto-filled by browser, clear it
                    if (this.value && this.value.length > 0 && document.activeElement !== this) {
                        this.value = '';
                    }
                });
            }
            
            if (usernameField) usernameField.focus();
        });
        
        // Button loading state on form submit
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[action="login.php"]');
            const loginBtn = document.querySelector('.btn-login');
            
            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.textContent = 'LOGGING IN';
                });
            }
        });
    </script>
    <script src="JS/security.js"></script>
</body>
</html>