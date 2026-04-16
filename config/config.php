<?php
// Application Configuration
define('APP_NAME', 'MiniStudent');
define('APP_URL', 'http://localhost/ministudents');
define('APP_ENV', 'development'); // development or production

// Security
define('SESSION_LIFETIME', 3600); // 1 hour
define('BCRYPT_ROUNDS', 12);

// Paths
define('BASE_PATH', __DIR__ . '/..');
define('VIEWS_PATH', BASE_PATH . '/views');

// Error reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>