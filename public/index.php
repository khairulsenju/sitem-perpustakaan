<?php

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Define base URL for asset paths
// For direct deployment to htdocs, BASE_URL should be empty
$baseUrl = ''; // Empty for root deployment

// Make it available globally
define('BASE_URL', $baseUrl);

// Enable debug mode for development
define('DEBUG', true);

use Arya\SistemPerpustakaan\Core\App;

// Load composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize the application
$app = new App();


// Run the application
$app->run();