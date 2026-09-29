<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/database.php';

// Set default timezone
date_default_timezone_set('Asia/Riyadh');

// Site Configuration
define('SITE_NAME', 'MASARAK');
define('SITE_URL', rtrim(getenv('MASARAK_SITE_URL') ?: 'http://localhost/MASARAK', '/'));

// Get current language from session or default to Arabic
function getCurrentLanguage() {
    return isset($_SESSION['language']) ? $_SESSION['language'] : 'ar';
}

// Set language
function setLanguage($lang) {
    if (in_array($lang, ['ar', 'en'])) {
        $_SESSION['language'] = $lang;
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
