<?php
/**
 * System Backup - Create full database backup
 */

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

$dal = new PortfolioDAL();
$timestamp = date('Y-m-d_H-i-s');
$backup_file = "backups/db_portfolio_backup_" . $timestamp . ".sql";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Backup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .info {
            background: #f0f4ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #667eea;
        }
        .info p {
            margin: 10px 0;
            color: #555;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin: 10px 5px;
            transition: background 0.3s;
        }
        button:hover {
            background: #5568d3;
        }
        .secondary {
            background: #6c757d;
        }
        .secondary:hover {
            background: #5a6268;
        }
        .status {
            margin: 30px 0;
            padding: 20px;
            border-radius: 8px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 System Backup Manager</h1>
        
        <div class="info">
            <p><strong>Database:</strong> db_portfolio</p>
            <p><strong>Backup Location:</strong> /backups/</p>
            <p><strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <div style="margin: 30px 0;">
            <h3>Backup Options</h3>
            
            <div style="margin: 20px 0;">
                <h4>📦 Full Database Backup</h4>
                <p style="color: #666;">Creates a complete SQL dump of all tables</p>
                <a href="api/backup.php" style="display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Create Full Backup</a>
            </div>

            <div style="margin: 20px 0;">
                <h4>📸 Section Snapshots</h4>
                <p style="color: #666;">Backup individual sections (Profile, Education, Projects, etc.)</p>
                <a href="api/snapshot.php" style="display: inline-block; padding: 10px 20px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Manage Snapshots</a>
            </div>
        </div>

        <div style="margin: 30px 0;">
            <button onclick="window.history.back()" class="secondary">← Back to Panel</button>
            <button onclick="window.location.href='panel.php'" class="secondary">Home</button>
        </div>

        <p style="color: #999; font-size: 0.9rem; margin-top: 20px;">
            Note: Backups are stored in the /backups/ folder. Download them regularly for safekeeping.
        </p>
    </div>
</body>
</html>
