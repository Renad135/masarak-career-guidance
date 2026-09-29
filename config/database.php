<?php
// Read connection settings from the environment; local defaults are for development only.
define('DB_HOST', getenv('MASARAK_DB_HOST') ?: '127.0.0.1');
define('DB_USER', getenv('MASARAK_DB_USER') ?: 'masarak');
define('DB_PASS', getenv('MASARAK_DB_PASSWORD') ?: '');
define('DB_NAME', getenv('MASARAK_DB_NAME') ?: 'masarak_db');
define('DB_CHARSET', 'utf8mb4');

// Create database connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        http_response_code(500);
        die('Database connection failed. Check the application configuration.');
    }
}
?>
