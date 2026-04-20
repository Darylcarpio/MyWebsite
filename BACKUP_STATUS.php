<?php
/**
 * Backup Status - View and manage backups
 */

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

// Get backup files
$backup_dir = __DIR__ . '/backups';
$backups = [];

if (is_dir($backup_dir)) {
    $files = array_diff(scandir($backup_dir), ['.', '..']);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $backups[] = [
                'name' => $file,
                'size' => filesize($backup_dir . '/' . $file),
                'date' => filemtime($backup_dir . '/' . $file),
                'path' => $backup_dir . '/' . $file
            ];
        }
    }
    usort($backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-box {
            background: #f0f4ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .btn-download {
            background: #28a745;
            color: white;
        }
        .btn-download:hover {
            background: #218838;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .btn-back {
            background: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Backup Status & Management</h1>

        <div class="stats">
            <div class="stat-box">
                <div class="stat-number"><?php echo count($backups); ?></div>
                <div class="stat-label">Backup Files</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">
                    <?php 
                    $total_size = array_sum(array_map(function($b) { return $b['size']; }, $backups));
                    echo number_format($total_size / 1024 / 1024, 2);
                    ?>
                    MB
                </div>
                <div class="stat-label">Total Size</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo count($backups) > 0 ? 'Yes' : 'No'; ?></div>
                <div class="stat-label">Backups Available</div>
            </div>
        </div>

        <?php if (count($backups) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Date</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><code style="background: #f5f5f5; padding: 5px 10px; border-radius: 3px;"><?php echo htmlspecialchars($backup['name']); ?></code></td>
                    <td><?php echo date('Y-m-d H:i:s', $backup['date']); ?></td>
                    <td><?php echo number_format($backup['size'] / 1024, 2); ?> KB</td>
                    <td>
                        <a href="backups/<?php echo urlencode($backup['name']); ?>" download class="btn btn-download">⬇️ Download</a>
                        <button class="btn btn-delete" onclick="if(confirm('Delete backup?')) window.location.href='delete_backup.php?file=' + encodeURIComponent('<?php echo $backup['name']; ?>')">🗑️ Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty">
            <p>📭 No backup files found yet.</p>
            <p><a href="SYSTEM_BACKUP.php">Create a backup now →</a></p>
        </div>
        <?php endif; ?>

        <div class="button-group">
            <button onclick="window.history.back()" class="btn btn-back">← Back</button>
            <button onclick="window.location.href='panel.php'" class="btn btn-back">Home</button>
        </div>
    </div>
</body>
</html>
