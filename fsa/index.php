<?php
// auth_routes.php

// Include Composer's autoloader (handles PSR-4 autoloading)
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from the .env file
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Before starting the session, configure cookie parameters
session_set_cookie_params([
    'lifetime' => 0,                // Session cookie persists until the browser is closed
    'path'     => '/',
    'domain'   => '.japropertysc.com', // Notice the leading dot for subdomain sharing
    'secure'   => true,             // Only send cookie over HTTPS
    'httponly' => true,             // Prevent JavaScript access to the cookie
    'samesite' => 'Lax'             // Adjust based on your needs (None, Lax, Strict)
]);

session_start();


// Simple routing using a query parameter (?route=)
// This file handles auth-specific routes only.
$route = $_GET['route'] ?? 'login';

switch ($route) {
    // Login Routes
    case 'login':
        // GET /login: Render login view; POST /login: Process login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\LoginController();
            $controller->processLogin();
        } else {
            $controller = new App\Controllers\LoginController();
            $controller->renderLoginView();

        }
        break;
    
    // Logout Route
    case 'logout':
        // GET /logout: Process logout
        $controller = new App\Controllers\LogoutController();
        $controller->processLogout();
        break;
    
    // Registration Routes
    case 'register':
        // GET /register: Render registration view; POST /register: Process registration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\RegisterController();
            $controller->processRegistration();
        } else {
            $controller = new App\Controllers\RegisterController();
            $controller->renderRegisterView();
        }
        break;
    
    // Account Activation Route
    case 'activate':
        // GET /activate?token=XYZ: Process account activation via token
        $controller = new App\Controllers\ActivateController();
        $controller->processActivation();
        break;
    
    // Forgot Password Routes
    case 'forgot-password':
        // GET /forgot-password: Render forgot password view; POST /forgot-password: Process request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\ForgotPasswordController();
            $controller->processForgotPassword();
        } else {
            $controller = new App\Controllers\ForgotPasswordController();
            $controller->renderForgotPasswordView();
        }
        break;
    
    // Reset Password Routes
    case 'reset-password':
        // GET /reset-password?token=XYZ: Render reset password view; POST /reset-password: Process reset
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\ResetPasswordController();
            $controller->processResetPassword();
        } else {
            $controller = new App\Controllers\ResetPasswordController();
            $controller->renderResetPasswordView();
        }
        break;
    
    // Fallback for undefined routes
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
}
