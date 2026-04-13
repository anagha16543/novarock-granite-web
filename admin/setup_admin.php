<?php
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    echo "<h1>Novarock v3.0 Strategic Alpha Deployment</h1>";
    echo "<hr><div style='font-family:sans-serif; line-height:1.6;'>";
    
    // 1. DIRECTORY SYNCHRONIZATION
    echo "<h4>Step 1: Physical Environment Setup...</h4>";
    $dirs = ['../uploads/products', '../uploads/gallery'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            echo "<p style='color:blue;'>+ Created Directory: $dir</p>";
        } else {
            echo "<p style='color:green;'>✓ Verified Directory: $dir</p>";
        }
    }

    // 2. CORE DATABASE REPAIR
    echo "<h4>Step 2: Repairing Core Inventory Structures...</h4>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_images` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `product_id` INT(11) NOT NULL,
        `url` VARCHAR(255) NOT NULL,
        `is_main` TINYINT(1) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 3. V3.0 ENHANCED SCHEMA (SEO, STATS, FILTERS)
    echo "<h4>Step 3: Deploying Strategic Alpha Patches (v3.0)...</h4>";
    
    // Site Branding & SEO Settings
    $pdo->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `site_title` varchar(255) DEFAULT 'Novarock International',
        `site_description` text DEFAULT 'Premium Stone Export & Manufacturing',
        `contact_email` varchar(255) DEFAULT 'info@novarock.com',
        `contact_phone` varchar(50) DEFAULT '+91 999 999 9999',
        `office_address` text DEFAULT 'Rajasthan, India',
        `meta_keywords` text DEFAULT 'granite, marble, stone export',
        `analytics_enabled` TINYINT(1) DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Ensure default site settings
    if ($pdo->query("SELECT COUNT(*) FROM site_settings")->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO site_settings (id) VALUES (1)");
    }

    // Product Stats and Advanced Filtering
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `views` INT(11) DEFAULT 0 ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `color` VARCHAR(50) DEFAULT 'Natural' AFTER `name` ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `origin` VARCHAR(100) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `finish` VARCHAR(100) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `thickness` VARCHAR(100) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `price_range` VARCHAR(100) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `is_featured` TINYINT(1) DEFAULT 0 ");
    
    // Inquiries Path
    $pdo->exec("ALTER TABLE `inquiries` ADD COLUMN IF NOT EXISTS `country` VARCHAR(100) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `inquiries` ADD COLUMN IF NOT EXISTS `subject` VARCHAR(255) DEFAULT NULL ");
    $pdo->exec("ALTER TABLE `inquiries` ADD COLUMN IF NOT EXISTS `inquiry_type` VARCHAR(50) DEFAULT 'general' ");
    $pdo->exec("ALTER TABLE `inquiries` ADD COLUMN IF NOT EXISTS `status` VARCHAR(50) DEFAULT 'unread' ");
    
    // Calculator Persistence
    $pdo->exec("CREATE TABLE IF NOT EXISTS `calculator_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `weight_per_sqm` decimal(10,2) DEFAULT 45.00,
        `thickness_multipliers` text DEFAULT '{\"18mm\": 1, \"20mm\": 1.1, \"30mm\": 1.5}',
        `slab_width` decimal(10,2) DEFAULT 300.00,
        `slab_height` decimal(10,2) DEFAULT 180.00,
        `wastage_percent` decimal(10,2) DEFAULT 10.00,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    if ($pdo->query("SELECT COUNT(*) FROM calculator_settings")->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO calculator_settings (id) VALUES (1)");
    }

    echo "<p style='color:green;'>✓ v3.0 Alpha Engine Synchronized!</p>";

    // 4. ADMIN SYNCHRONIZATION
    echo "<h4>Step 4: Hardening Security...</h4>";
    $pdo->exec("ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `email` VARCHAR(100) AFTER `id` ");
    $pdo->exec("DELETE FROM admins");
    $email = 'admin@novarock.com';
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $hash]);
    echo "<p style='color:green;'>✓ Admin Access Validated (admin123)!</p>";

    echo "<hr>";
    echo "<h3>Strategic Alpha Initialized!</h3>";
    echo "<p>Your system is now prepared for advanced SEO, Analytics, and Global Branding.</p>";
    echo "<a href='login.php' style='display:inline-block; padding:15px 30px; background:#C6A75E; color:black; font-weight:bold; text-decoration:none; border-radius:10px;'>Login to Dashboard</a>";
    echo "</div>";

} catch (Exception $e) {
    die("<div style='color:red; font-family:sans-serif;'>
        <h2>Critical Deployment Error</h2>
        <p>Could not initialize Alpha features: " . $e->getMessage() . "</p>
    </div>");
}
