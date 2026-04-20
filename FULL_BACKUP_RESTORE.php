<?php
/**
 * Full System Backup & Restore Manager
 */

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

$backup_dir = __DIR__ . '/backups';
@mkdir($backup_dir, 0755, true);

// Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'all';

// Get existing backups
$full_backups = [];
if (is_dir($backup_dir)) {
    $files = array_diff(scandir($backup_dir), ['.', '..']);
    foreach ($files as $file) {
        if (strpos($file, 'full_backup_') === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
            $full_backups[] = [
                'name' => $file,
                'size' => filesize($backup_dir . '/' . $file),
                'date' => filemtime($backup_dir . '/' . $file),
                'path' => $backup_dir . '/' . $file
            ];
        }
    }
    usort($full_backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full System Backup & Restore</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 1.1rem;
        }
        .section {
            background: white;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .section-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 2px solid #667eea;
            font-weight: bold;
            font-size: 1.1rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-content {
            padding: 30px;
        }
        .backup-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .backup-option {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }
        .backup-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .backup-option h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .backup-option p {
            color: #666;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f0f4ff;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .upload-area {
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background: #f8f9ff;
        }
        .upload-area.dragover {
            background: #e8ebff;
            border-color: #5568d3;
        }
        input[type="file"] {
            display: none;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Full System Backup & Restore Manager</h1>
            <p>Complete backup and recovery for your entire portfolio system</p>
        </div>

        <!-- BACKUP SECTION -->
        <div class="section" <?php echo ($action !== 'all' && $action !== 'backup') ? 'style="display:none;"' : ''; ?>>
            <div class="section-header">
                📦 System Backup Options
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <strong>ℹ️ What gets backed up:</strong> Database, all files, configuration, media files (images, audio)
                </div>

                <div class="backup-options">
                    <div class="backup-option">
                        <h3>🗄️ Database Only</h3>
                        <p>Backup just the database (lightweight, fast)</p>
                        <a href="api/backup.php?action=full-backup" class="btn btn-primary">Backup Database</a>
                    </div>

                    <div class="backup-option">
                        <h3>📦 Full System</h3>
                        <p>Backup database + files + media (complete)</p>
                        <a href="api/backup.php?action=full-backup-with-files" class="btn btn-success">Full Backup</a>
                    </div>

                    <div class="backup-option">
                        <h3>📸 Sections Only</h3>
                        <p>Backup individual sections (Profile, Projects, etc.)</p>
                        <a href="api/snapshot.php" class="btn btn-primary">Manage Snapshots</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATUS SECTION -->
        <div class="section" <?php echo $action !== 'all' ? 'style="display:none;"' : ''; ?>>
            <div class="section-header">
                📊 Backup Status & Management
            </div>
            <div class="section-content">
                <div class="stats">
                    <div class="stat-box">
                        <div class="stat-number"><?php echo count($full_backups); ?></div>
                        <div class="stat-label">Full Backups</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">
                            <?php 
                            $total_size = array_sum(array_map(function($b) { return $b['size']; }, $full_backups));
                            echo number_format($total_size / 1024 / 1024, 1);
                            ?>
                            MB
                        </div>
                        <div class="stat-label">Total Size</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">
                            <?php 
                            if (count($full_backups) > 0) {
                                echo date('M d', $full_backups[0]['date']);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                        <div class="stat-label">Latest Backup</div>
                    </div>
                </div>

                <?php if (count($full_backups) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Backup File</th>
                            <th>Date & Time</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($full_backups as $backup): ?>
                        <tr>
                            <td><code style="background: #f5f5f5; padding: 5px 10px; border-radius: 3px; font-size: 0.9rem;"><?php echo htmlspecialchars($backup['name']); ?></code></td>
                            <td><?php echo date('Y-m-d H:i:s', $backup['date']); ?></td>
                            <td><?php echo number_format($backup['size'] / 1024 / 1024, 2); ?> MB</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="backups/<?php echo urlencode($backup['name']); ?>" download class="btn btn-success btn-small">⬇️ Download</a>
                                    <button class="btn btn-danger btn-small" onclick="if(confirm('Delete this backup?')) window.location.href='api/backup.php?action=delete&file=' + encodeURIComponent('<?php echo $backup['name']; ?>')">🗑️ Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty">
                    <p>📭 No full backups created yet.</p>
                    <p style="margin-top: 15px;">
                        <a href="api/backup.php?action=full-backup" class="btn btn-success">Create First Backup →</a>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RESTORE SECTION -->
        <div class="section" <?php echo ($action !== 'all' && $action !== 'restore') ? 'style="display:none;"' : ''; ?>>
            <div class="section-header">
                ♻️ Restore Options
            </div>
            <div class="section-content">
                <div class="alert alert-warning">
                    <strong>⚠️ Warning:</strong> Restoring will overwrite current data. Make sure you have a backup first!
                </div>

                <div class="backup-options">
                    <div class="backup-option">
                        <h3>📌 Section Restore</h3>
                        <p>Restore individual sections from snapshots</p>
                        <p style="font-size: 0.85rem; color: #999;">Use the restore buttons in the admin panel</p>
                    </div>

                    <div class="backup-option">
                        <h3>🗄️ Restore from Backup</h3>
                        <p>Upload and restore a .zip or .sql backup file</p>
                        <form id="restoreForm" enctype="multipart/form-data" style="margin-top: 10px;">
                            <input type="file" id="restoreFile" name="restore_file" accept=".zip,.sql" required>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('restoreFile').click()">📂 Select File</button>
                        </form>
                    </div>
                </div>

                <div style="background: #f8f9ff; padding: 20px; border-radius: 6px; margin-top: 20px;">
                    <h3 style="color: #333; margin-bottom: 15px;">📋 Restore Instructions</h3>
                    <ol style="color: #666; line-height: 1.8;">
                        <li><strong>Create a backup</strong> using "Full Backup" or "Database Only"</li>
                        <li><strong>Download the backup file</strong> to your computer</li>
                        <li><strong>To restore:</strong> Upload the .zip or .sql file here</li>
                        <li><strong>Confirm the restore</strong> - system will restore all data</li>
                        <li><strong>Refresh your browser</strong> to see restored content</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- QUICK ACCESS -->
        <div class="section" <?php echo $action !== 'all' ? 'style="display:none;"' : ''; ?>>
            <div class="section-header">
                🔗 Quick Access Links
            </div>
            <div class="section-content">
                <div class="backup-options">
                    <div class="backup-option" style="border-color: #28a745;">
                        <h3>💾 Quick Backup</h3>
                        <p>Focus on backup options</p>
                        <a href="?action=backup" class="btn btn-success">Backup ➜</a>
                    </div>

                    <div class="backup-option" style="border-color: #dc3545;">
                        <h3>♻️ Quick Restore</h3>
                        <p>Focus on restore options</p>
                        <a href="?action=restore" class="btn btn-danger">Restore ➜</a>
                    </div>

                    <div class="backup-option" style="border-color: #667eea;">
                        <h3>📊 All Options</h3>
                        <p>Full backup & restore manager</p>
                        <a href="?" class="btn btn-primary">Full View ➜</a>
                    </div>

                    <div class="backup-option" style="border-color: #667eea;">
                        <h3>⬅️ Back to Admin</h3>
                        <p>Return to admin panel main menu</p>
                        <a href="panel.php" class="btn btn-secondary">Admin Panel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('restoreFile')?.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const file = this.files[0];
                if (!confirm(`Restore from ${file.name}?\n\nThis will overwrite all current data!`)) {
                    this.value = '';
                    return;
                }
                
                const formData = new FormData();
                formData.append('action', 'restore');
                formData.append('restore_file', file);
                
                // Upload and restore
                fetch('api/backup.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Restore completed successfully!\n\nRefresh the page to see changes.');
                        window.location.reload();
                    } else {
                        alert('❌ Restore failed: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(e => {
                    alert('❌ Error: ' + e.message);
                });
            }
        });
    </script>
    <script src="JS/security.js"></script>
</body>
</html>
