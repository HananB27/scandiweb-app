<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight request - return 200 response with headers only
    http_response_code(200);
    exit();
}

try {
    // Autoload dependencies
    require_once __DIR__ . '/../vendor/autoload.php';

    // Initialize the FastRoute dispatcher
    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
    });

    // Dispatch the request
    $routeInfo = $dispatcher->dispatch(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']
    );

    // Log the route information for debugging
    error_log("Route Info: " . print_r($routeInfo, true));

    // Handle the route based on the dispatch result
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            error_log("Route not found: " . $_SERVER['REQUEST_URI']);
            http_response_code(404);
            echo '404 Not Found';
            break;

        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            error_log("Method not allowed. Allowed methods: " . implode(", ", $allowedMethods));
            http_response_code(405);
            echo '405 Method Not Allowed';
            break;

        case FastRoute\Dispatcher::FOUND:
            // Extract the handler and route variables
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            // Log handler and variables for debugging
            error_log("Handler: " . print_r($handler, true));
            error_log("Vars: " . print_r($vars, true));

            try {
                // Execute the handler function and output the result
                echo $handler($vars);
            } catch (Exception $e) {
                // Log any exceptions in the handler
                error_log("Error in handler: " . $e->getMessage());
                http_response_code(500);
                echo "Internal Server Error";
            }
            break;
    }
} catch (Exception $e) {
    // Catch any fatal errors in the script
    error_log("Fatal Error: " . $e->getMessage());
    http_response_code(500);
    echo "Fatal Error";
}
