<?php
/**
 * Database Initialization - Clean & Simple
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'db_portfolio';

try {
    // Connect to MySQL (no database yet)
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "<h2>🔧 Database Initialization Starting...</h2><hr>";

    // CREATE database if not exists
    echo "Creating/Using database...<br>";
    try {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (Exception $e) {
        echo "Database already exists, continuing...<br>";
    }
    $pdo->exec("USE `$db`");
    
    // DROP old tables if they exist
    echo "Cleaning up old tables...<br>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    
    $tables = ['data_snapshots', 'page_views', 'iso_feedback', 'activity_log', 'contact_info', 
               'contact_types', 'hobby_items', 'hobby_categories', 'project_technologies', 
               'technologies', 'projects', 'project_status', 'education_achievements', 
               'education', 'profile', 'admin_users'];
    
    foreach ($tables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
        } catch (Exception $e) {
            // Continue even if drop fails
        }
    }
    
    echo "✓ Database created<br><hr>";
    
    // Admin Users
    $pdo->exec("CREATE TABLE admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100),
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        is_active BOOLEAN DEFAULT 1
    )");
    echo "✓ admin_users table<br>";

    // Profile
    $pdo->exec("CREATE TABLE profile (
        id INT PRIMARY KEY DEFAULT 1,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        location VARCHAR(100),
        professional_title VARCHAR(100),
        bio TEXT,
        profile_image VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✓ profile table<br>";

    // Education
    $pdo->exec("CREATE TABLE education (
        id INT AUTO_INCREMENT PRIMARY KEY,
        year_range VARCHAR(20),
        title VARCHAR(100),
        school VARCHAR(100),
        description TEXT,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✓ education table<br>";

    // Education Achievements
    $pdo->exec("CREATE TABLE education_achievements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        education_id INT NOT NULL,
        achievement TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (education_id) REFERENCES education(id) ON DELETE CASCADE
    )");
    echo "✓ education_achievements table<br>";

    // Project Status
    $pdo->exec("CREATE TABLE project_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        status_name VARCHAR(50) UNIQUE NOT NULL
    )");
    echo "✓ project_status table<br>";

    // Projects
    $pdo->exec("CREATE TABLE projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        image VARCHAR(255),
        status_id INT DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (status_id) REFERENCES project_status(id)
    )");
    echo "✓ projects table<br>";

    // Technologies
    $pdo->exec("CREATE TABLE technologies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tech_name VARCHAR(100) UNIQUE NOT NULL
    )");
    echo "✓ technologies table<br>";

    // Project Technologies
    $pdo->exec("CREATE TABLE project_technologies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        technology_id INT NOT NULL,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (technology_id) REFERENCES technologies(id) ON DELETE CASCADE,
        UNIQUE KEY unique_project_tech (project_id, technology_id)
    )");
    echo "✓ project_technologies table<br>";

    // Hobby Categories
    $pdo->exec("CREATE TABLE hobby_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(50) UNIQUE NOT NULL,
        display_name VARCHAR(100),
        category_icon VARCHAR(50),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ hobby_categories table<br>";

    // Hobby Items
    $pdo->exec("CREATE TABLE hobby_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        item_name VARCHAR(100) NOT NULL,
        image VARCHAR(255),
        audio_file VARCHAR(255),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES hobby_categories(id) ON DELETE CASCADE
    )");
    echo "✓ hobby_items table<br>";

    // Contact Types
    $pdo->exec("CREATE TABLE contact_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_name VARCHAR(50) UNIQUE NOT NULL,
        icon VARCHAR(50),
        sort_order INT DEFAULT 0
    )");
    echo "✓ contact_types table<br>";

    // Contact Info
    $pdo->exec("CREATE TABLE contact_info (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_id INT NOT NULL,
        value VARCHAR(255) NOT NULL,
        link VARCHAR(255),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (type_id) REFERENCES contact_types(id) ON DELETE CASCADE
    )");
    echo "✓ contact_info table<br>";

    // Activity Log
    $pdo->exec("CREATE TABLE activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT,
        action VARCHAR(100) NOT NULL,
        table_name VARCHAR(50),
        record_id INT,
        details TEXT,
        ip_address VARCHAR(45),
        user_agent VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_admin_id (admin_id),
        INDEX idx_created_at (created_at),
        FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )");
    echo "✓ activity_log table<br>";

    // Page Views
    $pdo->exec("CREATE TABLE page_views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page VARCHAR(100),
        ip_address VARCHAR(45),
        user_agent VARCHAR(255),
        referer VARCHAR(255),
        viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_page (page),
        INDEX idx_viewed_at (viewed_at)
    )");
    echo "✓ page_views table<br>";

    // ISO Feedback
    $pdo->exec("CREATE TABLE iso_feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reviewer_name VARCHAR(100),
        functional_suitability INT,
        performance_efficiency INT,
        compatibility INT,
        usability INT,
        reliability INT,
        security INT,
        feedback TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ iso_feedback table<br>";

    // Data Snapshots
    $pdo->exec("CREATE TABLE data_snapshots (
        section VARCHAR(100) PRIMARY KEY,
        snapshot_data LONGTEXT NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✓ data_snapshots table<br>";

    echo "<hr>";
    echo "<h3>✓ All tables created!</h3>";

    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // Insert Default Data
    echo "<h3>📋 Inserting Default Data...</h3>";

    // Admin User (with new secure password)
    $hash = password_hash('P@ssword66', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admin_users (username, email, password_hash) VALUES (?, ?, ?)")
        ->execute(['Daryl Carpio', 'daryl@portfolio.local', $hash]);
    echo "✓ Admin user: Daryl Carpio / P@ssword66<br>";

    // Profile
    $pdo->prepare("INSERT INTO profile (id, full_name, email, professional_title, bio) VALUES (?, ?, ?, ?, ?)")
        ->execute([1, 'Bryan Darryl Carpio', 'carpiodarryl@gmail.com', 'Software Developer', 'Welcome to my portfolio']);
    echo "✓ Profile created<br>";

    // Project Statuses
    foreach (['Completed', 'In Progress', 'Planning'] as $status) {
        $pdo->prepare("INSERT INTO project_status (status_name) VALUES (?)")->execute([$status]);
    }
    echo "✓ Project statuses created<br>";

    // Contact Types
    $contacts = [
        ['Email', '✉️'],
        ['Phone', '📱'],
        ['LinkedIn', '💼'],
        ['Facebook', '👤'],
        ['GitHub', '💻'],
        ['Twitter', '🐦'],
        ['Address', '📍']
    ];
    foreach ($contacts as [$name, $icon]) {
        $pdo->prepare("INSERT INTO contact_types (type_name, icon) VALUES (?, ?)")->execute([$name, $icon]);
    }
    echo "✓ Contact types created<br>";

    echo "<hr>";
    echo "<h2 style='color:green'>✅ DATABASE READY!</h2>";
    echo "<p><strong>Login Credentials:</strong><br>";
    echo "Username: <code>admin</code><br>";
    echo "Password: <code>admin123</code></p>";
    echo "<p><a href='admin_login.php' style='padding:10px 20px; background:#667eea; color:white; text-decoration:none; border-radius:5px; display:inline-block;'>Go to Admin Login →</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ ERROR</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
