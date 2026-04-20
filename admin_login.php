<?php
/**
 * Admin Login Fix & Access
 * Direct login without redirect issues
 */

session_start();

echo "<h1>🔐 Direct Admin Access</h1>";
echo "<hr>";

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_portfolio';

try {
    // Connect using PDO for consistency
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Check if form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['do_login'])) {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        echo "<h3>Processing Login...</h3>";
        
        if (empty($username) || empty($password)) {
            echo '<p style="color: red;">❌ Please enter both username and password</p>';
        } else {
            // Query admin user
            $stmt = $pdo->prepare("SELECT id, username, email, password_hash FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$admin) {
                echo '<p style="color: red;">❌ Username not found</p>';
            } else {
                echo '<p>Username found: ' . htmlspecialchars($admin['username']) . '</p>';
                
                // Verify password
                if (password_verify($password, $admin['password_hash'])) {
                    echo '<p style="color: green;">✅ Password verified!</p>';
                    
                    // Set session
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_user'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_login_time'] = time();
                    
                    echo '<p style="color: green;">✅ Session created!</p>';
                    
                    // Show redirect button
                    echo '<p style="margin-top: 30px;">';
                    echo '<a href="http://localhost/MyWebsite/panel.php" style="display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">Go to Admin Panel →</a>';
                    echo '</p>';
                    
                    // Also try auto-redirect
                    echo '<script>';
                    echo 'setTimeout(function() {';
                    echo '  window.location.href = "http://localhost/MyWebsite/panel.php";';
                    echo '}, 2000);';
                    echo '</script>';
                    
                } else {
                    echo '<p style="color: red;">❌ Password incorrect</p>';
                }
            }
        }
    } else {
        // Show login form
        echo "<h3>Admin Login Form</h3>";
        
        // Check if admin exists
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_users WHERE username = 'admin'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            echo '<p style="color: red;">❌ No admin account found - creating one...</p>';
            
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
            $stmt->execute(['admin', $hash, 'admin@portfolio.local']);
            
            echo '<p style="color: green;">✅ Admin account created!</p>';
        }
        
        echo '<form method="POST" style="background: #f9f9f9; padding: 30px; border-radius: 8px; max-width: 400px; margin: 20px 0;">';
        echo '<input type="hidden" name="do_login" value="1">';
        
        echo '<div style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 8px; font-weight: bold;">Username:</label>';
        echo '<input type="text" name="username" value="admin" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;" required autofocus>';
        echo '</div>';
        
        echo '<div style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 8px; font-weight: bold;">Password:</label>';
        echo '<input type="password" name="password" value="admin123" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;" required>';
        echo '</div>';
        
        echo '<button type="submit" style="width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 16px;">Login to Admin Panel</button>';
        echo '</form>';
        
        echo '<hr>';
        echo '<p><strong>Default Credentials:</strong></p>';
        echo '<ul>';
        echo '<li><strong>Username:</strong> admin</li>';
        echo '<li><strong>Password:</strong> admin123</li>';
        echo '</ul>';
        
        echo '<p><strong>What you can do in Admin Panel:</strong></p>';
        echo '<ul>';
        echo '<li>✏️ Edit your profile (name, bio, vision)</li>';
        echo '<li>📚 Add/Edit/Delete education entries</li>';
        echo '<li>🚀 Add/Edit/Delete projects</li>';
        echo '<li>🎯 Manage hobbies and skills</li>';
        echo '<li>📧 Update contact information</li>';
        echo '<li>🗑️ Delete sample data if you want</li>';
        echo '</ul>';
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Error: ' . $e->getMessage() . '</p>';
}
?>

<style>
body { font-family: Arial; margin: 20px; background-color: #f5f5f5; }
h1, h2, h3 { color: #333; }
hr { margin: 30px 0; border: none; border-top: 1px solid #e0e0e0; }
a { color: #667eea; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
<script src="JS/security.js"></script>
</body>
</html>
