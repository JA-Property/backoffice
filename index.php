<?php
/**
 * index.php - Entry point for the staff portal.
 *
 * This file initializes the environment, starts the session, checks authentication,
 * and routes the request to the appropriate controller or view using a custom routing system.
 */

// -----------------------------------------------------------------------------
// 1. INITIAL SETUP & ENVIRONMENT CONFIGURATION
// -----------------------------------------------------------------------------

// Autoload dependencies using Composer
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from a .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// -----------------------------------------------------------------------------
// 2. SESSION INITIALIZATION & AUTHENTICATION CHECK
// -----------------------------------------------------------------------------

// Initialize the session with custom cookie parameters for security and proper domain scoping
initializeSession();

// Check if the user is authenticated and has the proper role for the staff portal
checkAuthentication();

// -----------------------------------------------------------------------------
// 3. ROUTING SETUP
// -----------------------------------------------------------------------------

// Get the normalized request URI (path without trailing slash)
$requestUri = getRequestUri();

// Define application routes as an array of routes. Each route contains:
// - a 'pattern' that uses regular expressions to match the URI.
// - a 'handler' callback function that will be executed if the route matches.
$routes = [
    // Dashboard route (static)
    [
        'pattern' => '#^(?:/|/dashboard)$#',
        'handler' => function () {
            $controller = new \App\Controllers\DashboardController();
            $controller->index();
        }
    ],
    // Dynamic customer route e.g. /customers/123
    [
        'pattern' => '#^/customers/(\d+)$#',
        'handler' => function ($matches) {
            // Pass the captured customer ID to the controller via GET variable
            $_GET['id'] = $matches[1];
            $controller = new \App\Controllers\CustomerController();
            $controller->renderSingleCustomer();
        }
    ],
   // Finance routes
[
    'pattern' => '#^/finance/journal/new$#',
    'handler' => function () {
        // Create an instance of the FinanceController and call the method for new journal entries
        $controller = new \App\Controllers\FinanceController();
        $controller->newJournalEntry();
    }
],
[
    'pattern' => '#^/finance/journal/view$#',
    'handler' => function () {
        // Create an instance of the FinanceController and call the method to view journal entries
        $controller = new \App\Controllers\FinanceController();
        $controller->viewJournalEntry();
    }
],
[
    'pattern' => '#^/finance/expense/view$#',
    'handler' => function () {
        // Create an instance of the FinanceController and call the method to view an expense
        $controller = new \App\Controllers\FinanceController();
        $controller->viewExpense();
    }
],
[
    'pattern' => '#^/finance/expense/new$#',
    'handler' => function () {
        // Create an instance of the FinanceController and call the method to create a new expense
        $controller = new \App\Controllers\FinanceController();
        $controller->newExpense();
    }
],
// Mobile-specific finance route
[
    'pattern' => '#^/finance/expense/new$#',
    'handler' => function () {
        // Create an instance of the FinanceController and call the method to create a new expense for mobile
        $controller = new \App\Controllers\FinanceController();
        $controller->mobileNewExpense();
    }
],
// Category search route
// In your index.php or router
[
    'pattern' => '#^/finance/category/search$#',
    'handler' => function () {
        $controller = new \App\Controllers\Finance\CategoryController();
        $controller->search();
    }
],



    // Customer management routes
    [
        'pattern' => '#^/customers/all$#',
        'handler' => function () {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderAllCustomers();
        }
    ],
    
    [
        'pattern' => '#^/customers/view$#',
        'handler' => function () {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderSingleCustomer();
        }
    ],
    [
        'pattern' => '#^/customers/new$#',
        'handler' => function () {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderNewCustomer();
        }
    ],
    // Property routes for customers
    [
        'pattern' => '#^/customers/(\d+)/properties/new$#',
        'handler' => function ($matches) {
            $customerId = $matches[1];
            $controller = new \App\Controllers\PropertyController();
            $controller->renderNewProperty($customerId);
        }
    ],
    [
        'pattern' => '#^/customers/(\d+)/properties/newrental$#',
        'handler' => function ($matches) {
            $customerId = $matches[1];
            $controller = new \App\Controllers\PropertyController();
            $controller->renderNewRentalProperty($customerId);
        }
    ]
];

// -----------------------------------------------------------------------------
// 4. ROUTE PROCESSING
// -----------------------------------------------------------------------------

$foundRoute = false; // Flag to determine if a route was matched

// Loop through each defined route and test if it matches the current URI
foreach ($routes as $route) {
    if (preg_match($route['pattern'], $requestUri, $matches)) {
        // If a match is found, execute the route's handler callback.
        // Pass any regex capture groups to the handler.
        call_user_func($route['handler'], $matches);
        $foundRoute = true;
        break; // Stop processing once a matching route is found.
    }
}

// If no route was matched, render a fallback view (could be a 404 page or a default dashboard)
if (!$foundRoute) {
    renderView('/app/Views/AdminDashboard.php');
}

// -----------------------------------------------------------------------------
// FUNCTION DEFINITIONS
// -----------------------------------------------------------------------------

/**
 * Initialize session settings including custom cookie parameters.
 *
 * This function configures the session cookie to enforce security best practices.
 */
function initializeSession() {
    // Retrieve current session cookie parameters
    $cookieParams = session_get_cookie_params();
    // Set new parameters with secure options such as domain scoping and samesite policy
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path'     => '/',
        'domain'   => '.example.com', // Adjust to your domain
        'secure'   => $cookieParams['secure'],
        'httponly' => $cookieParams['httponly'],
        'samesite' => 'None',
    ]);
    
    // Set a custom session name
    session_name('MYSESSIONID'); 
    // Start the session
    session_start();
}

/**
 * Check user authentication and role authorization.
 *
 * If the user is not logged in or does not have the proper role, they are redirected
 * to the appropriate authentication or customer portal.
 */
function checkAuthentication() {
    // Redirect unauthenticated users to the authentication service
    if (!isset($_SESSION['user'])) {
        header('Location: https://auth.japropertysc.com');
        exit;
    }
    // Redirect users with the 'customer' role to the customer portal
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'customer') {
        header('Location: https://customer.japropertysc.com');
        exit;
    }
}

/**
 * Get and normalize the request URI.
 *
 * @return string Returns the request URI without any trailing slash.
 */
function getRequestUri() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Normalize the URI by removing the trailing slash for consistency
    return rtrim($uri, '/');
}

/**
 * Render a PHP view file.
 *
 * This function starts output buffering, includes the specified view file,
 * and then outputs the buffered content. The script exits immediately afterward.
 *
 * @param string $viewPath Relative path to the PHP view file.
 */
function renderView($viewPath) {
    // Start output buffering to capture view output
    ob_start();
    require_once __DIR__ . $viewPath;
    // Output the buffered content and clean the buffer
    echo ob_get_clean();
    exit;
}
?>
