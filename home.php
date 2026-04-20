<?php
/**
 * Portfolio System - Home Launcher
 * Quick access to User Dashboard and Admin Panel
 */

// Check if already logged in as admin
$is_admin = isset($_SESSION) && isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio System - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            width: 100%;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
            animation: slideDown 0.6s ease-out;
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .launcher-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .launcher-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }

        .launcher-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .launcher-card.admin {
            border-top: 5px solid #dc3545;
        }

        .launcher-card.user {
            border-top: 5px solid #28a745;
        }

        .launcher-card.system {
            border-top: 5px solid #667eea;
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .launcher-card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        .launcher-card p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-user {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-user:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-admin {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }

        .btn-admin:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .btn-system {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-system:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .info-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease-out;
        }

        .info-section h3 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-box {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .info-box h4 {
            color: #333;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .info-box p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .credentials {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .credentials h5 {
            color: #856404;
            margin-bottom: 10px;
        }

        .credentials code {
            background: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 40px;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .launcher-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .launcher-card {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎓 Portfolio System</h1>
            <p>Bryan Darryl Carpio - ASCOT BSIT Student</p>
        </div>

        <!-- Main Launchers -->
        <div class="launcher-grid">
            <!-- User Dashboard -->
            <div class="launcher-card user">
                <div class="icon">🌍</div>
                <h2>Public Portfolio</h2>
                <p>View the complete portfolio website as a visitor. Showcase your projects, education, and skills.</p>
                <a href="dashboard.php" class="btn btn-user">📖 View Portfolio</a>
            </div>

            <!-- Admin Panel -->
            <div class="launcher-card admin">
                <div class="icon">🔐</div>
                <h2>Admin Panel</h2>
                <p>Login to manage your portfolio content. Add projects, education, hobbies, and more.</p>
                <a href="admin_login.php" class="btn btn-admin">🔑 Admin Login</a>
            </div>

            <!-- System Management -->
            <div class="launcher-card system">
                <div class="icon">⚙️</div>
                <h2>System Settings</h2>
                <p>Backup & restore your entire system. Manage database and files securely.</p>
                <a href="FULL_BACKUP_RESTORE.php" class="btn btn-system">🔐 Backup & Restore</a>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <h3>📋 Quick Info</h3>
            
            <div class="credentials">
                <h5>🔐 Default Admin Credentials:</h5>
                <p>Username: <code>admin</code> | Password: <code>admin123</code></p>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <h4>🌐 Website URLs</h4>
                    <p>
                        <strong>Portfolio:</strong><br>
                        <code style="font-size: 0.9rem;">http://localhost/MyWebsite/</code>
                    </p>
                </div>

                <div class="info-box">
                    <h4>🖥️ Batch Files</h4>
                    <p>
                        • LAUNCHER.bat - Menu<br>
                        • VIEW_PORTFOLIO.bat - Dashboard<br>
                        • ADMIN_LOGIN.bat - Login<br>
                        • ADMIN_PANEL.bat - Panel
                    </p>
                </div>

                <div class="info-box">
                    <h4>📊 Database</h4>
                    <p>
                        <strong>Name:</strong> db_portfolio<br>
                        <strong>User:</strong> root<br>
                        <strong>Host:</strong> localhost
                    </p>
                </div>

                <div class="info-box">
                    <h4>🔄 Backup Features</h4>
                    <p>
                        • Full system backups<br>
                        • Section snapshots<br>
                        • Quick restore<br>
                        • Download backups
                    </p>
                </div>

                <div class="info-box">
                    <h4>✨ Admin Features</h4>
                    <p>
                        • Profile management<br>
                        • Education tracking<br>
                        • Project showcase<br>
                        • Hobbies & skills
                    </p>
                </div>

                <div class="info-box">
                    <h4>📱 Public Features</h4>
                    <p>
                        • Responsive design<br>
                        • Portfolio display<br>
                        • Project showcase<br>
                        • Contact info
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Portfolio System v2.0 | ASCOT BSIT Student</p>
            <p>Last updated: <?php echo date('F Y'); ?></p>
        </div>
    </div>
    <script src="JS/security.js"></script>
</body>
</html>
