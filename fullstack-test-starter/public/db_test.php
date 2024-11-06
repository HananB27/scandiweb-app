<?php
// Enable error reporting to see any PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add CORS headers to allow requests from localhost:3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

// Include Composer's autoload file
require_once __DIR__ . '/../vendor/autoload.php'; // Make sure the path is correct

use App\Controller\Config\Database;

// Create a new instance of the Database class
$database = new Database();

// Test database connection
$conn = $database->connect();

// Prepare a response
if ($conn) {
    $response = [
        'status' => 'success',
        'message' => 'Database connection successful!'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Failed to connect to the database.'
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
