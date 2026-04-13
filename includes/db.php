<?php
/**
 * Novarock International - Database Connection
 * Configured for Hostinger MySQL
 */

// Deployment Config
$host = '193.203.184.214';
$db   = 'u278810541_novarock';
$user = 'u278810541_novarock';
$pass = 'Novarock123#'; // Hostinger Database Password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log error instead of displaying it
    die("Database Connection Error: " . $e->getMessage());
}

/**
 * Common Helper Functions
 */

// Sanitize output
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}

// Check if admin is logged in
function is_logged_in() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_id']);
}

// Require admin login
function require_login() {
    if (!is_logged_in()) {
        // Redirect to login.php in the current directory (admin folder)
        header("Location: login.php");
        exit;
    }
}
?>
