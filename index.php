<?php
// index.php - Entry point for the staff portal
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// (Optional) Customize session cookie params
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path'     => '/',
    'domain'   => '.example.com',
    'secure'   => $cookieParams['secure'],
    'httponly' => $cookieParams['httponly'],
    'samesite' => 'None',
]);

session_name('MYSESSIONID'); 
session_start();

// 1) AUTH CHECK
if (!isset($_SESSION['user'])) {
    header('Location: https://auth.japropertysc.com');
    exit;
}
if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'customer') {
    header('Location: https://customer.japropertysc.com');
    exit;
}

// 2) PARSE ROUTE
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/'); // remove trailing slash

// 3) ROUTING LOGIC
switch ($requestUri) {
    // Static routes
    case '':
    case '/':
    case '/dashboard':
        $controller = new \App\Controllers\DashboardController();
        $controller->index();
        exit;  // We call exit so no further code runs

    default:
        // === DYNAMIC OR ADDITIONAL ROUTES ===
        // e.g. /customers/123
        if (preg_match('#^/customers/(\d+)$#', $requestUri, $matches)) {
            $_GET['id'] = $matches[1];
            $controller = new \App\Controllers\CustomerController();
            $controller->renderSingleCustomer();
            exit;
        }
        // Finance routes
        elseif ($requestUri === '/finance/journal/new') {
            ob_start();
            require_once __DIR__ . '/app/Views/Finance/NewJournalEntryView.php';
            $content = ob_get_clean();
            echo $content;
            exit;
        } elseif ($requestUri === '/finance/journal/view') {
            ob_start();
            require_once __DIR__ . '/app/Views/Finance/JournalEntryView.php';
            $content = ob_get_clean();
            echo $content;
            exit;
        } elseif ($requestUri === '/finance/expense/view') {
            ob_start();
            require_once __DIR__ . '/app/Views/Finance/ExistingExpenseView.php';
            $content = ob_get_clean();
            echo $content;
            exit;
        } elseif ($requestUri === '/finance/expense/new') {
            ob_start();
            require_once __DIR__ . '/app/Views/Finance/FieldTechNewExpenseView.php';
            $content = ob_get_clean();
            echo $content;
            exit;
        }
        // Customer routes
        elseif ($requestUri === '/customers/all') {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderAllCustomers();
            exit;
        } elseif ($requestUri === '/customers/view') {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderSingleCustomer();
            exit;
        } elseif ($requestUri === '/customers/new') {
            $controller = new \App\Controllers\CustomerController();
            $controller->renderNewCustomer();
            exit;
        }
        // e.g. /customers/123/properties/new
        elseif (preg_match('#^/customers/(\d+)/properties/new$#', $requestUri, $matches)) {
            $customerId = $matches[1];
            $controller = new \App\Controllers\PropertyController();
            $controller->renderNewProperty($customerId);
            exit;
        }
        // e.g. /customers/123/properties/newrental
        elseif (preg_match('#^/customers/(\d+)/properties/newrental$#', $requestUri, $matches)) {
            $customerId = $matches[1];
            $controller = new \App\Controllers\PropertyController();
            $controller->renderNewRentalProperty($customerId);
            exit;
        }
        // 404 or fallback route
        else {
            // Example: show some fallback staff dashboard
            ob_start();
            require_once __DIR__ . '/app/Views/StaffDashboardView.php';
            $content = ob_get_clean();
            echo $content;
            exit;
        }
}
