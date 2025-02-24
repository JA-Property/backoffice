<?php
namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        // Here, we simply load the Dashboard index view.
        // The "Staff.php" layout is included INSIDE that view.
        require __DIR__ . '/../../app/Views/AdminDashboard.php';
    }
}
