<?php
/**
 * /app/Controllers/CustomerController.php
 *
 * Responsible for handling all customer-related operations within the application, including:
 *  - Rendering lists of customers (search, sort, pagination)
 *  - Batch actions (mark overdue, send email, export)
 *  - Viewing a single customer’s details
 *  - Creating new customers
 *  - Rendering "New Customer" form
 *
 * Copyright:
 *  (c) 2025 JA Property Management LLC. All rights reserved.
 *  Confidential & Proprietary
 *
 * @package   App\Controllers
 * @author    JA Property Management LLC.
 * @version   0.1.0
 */

namespace App\Controllers;

use App\Models\Customer;
use Exception;

/**
 * Class CustomerController
 *
 * Provides endpoints and methods for managing customers,
 * including listing, viewing, creating, and batch actions.
 */
class CustomerController
{
    /**
     * Renders the "All Customers" page, handling search, sorting, pagination,
     * and optional batch actions (POST).
     *
     * @return void
     */
    public function renderAllCustomers(): void
    {
        // 1) Possibly handle batch actions if POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBatchActions();
        }

        // 2) Gather query parameters
        $searchTerm   = trim($_GET['search'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');
        $sortColumn   = trim($_GET['sort']   ?? 'name');
        $sortOrder    = strtoupper(trim($_GET['order'] ?? 'ASC'));

        // Whitelist columns & orders
        $allowedSortCols = ['name', 'propertyCount', 'phone', 'email', 'opening_balance'];
        if (!in_array($sortColumn, $allowedSortCols, true)) {
            $sortColumn = 'name';
        }
        $sortOrder = in_array($sortOrder, ['ASC','DESC'], true) ? $sortOrder : 'ASC';

        // 3) Pagination
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $pageSize = max(1, (int)($_GET['pageSize'] ?? 10));
        $offset   = ($page - 1) * $pageSize;

        // 4) Fetch data from model
        $customers    = Customer::getCustomers($searchTerm, $statusFilter, $sortColumn, $sortOrder, $offset, $pageSize);
        $totalCount   = Customer::getCustomersCount($searchTerm, $statusFilter);
        $totalBalance = Customer::getTotalBalance();
        $totalPages   = max(1, ceil($totalCount / $pageSize));

        // 5) Prepare data for the view
        $data = [
            'searchTerm'   => $searchTerm,
            'statusFilter' => $statusFilter,
            'sortColumn'   => $sortColumn,
            'sortOrder'    => $sortOrder,
            'page'         => $page,
            'pageSize'     => $pageSize,
            'customers'    => $customers,
            'totalCount'   => $totalCount,
            'totalBalance' => $totalBalance,
            'totalPages'   => $totalPages,
            'headerIcon'   => 'fa-users',
            'headerTitle'  => 'All Customers',
            'pageTitle'    => 'All Customers',
        ];

        // 6) Render using our helper
        $this->renderView('Customers/AllCustomersView.php', $data);
    }

    /**
     * Handles batch actions when the request is POST (markOverdue, sendEmail, export).
     *
     * @return void
     */
    private function handleBatchActions(): void
    {
        $action      = $_POST['batchAction']  ?? null;
        $customerIds = $_POST['customerIds']  ?? [];

        // If no action or no IDs, do nothing
        if (!$action || empty($customerIds)) {
            return;
        }

        $customerIds = array_map('intval', $customerIds);

        switch ($action) {
            case 'markOverdue':
                $overdueId = Customer::getStatusIdByName('Overdue');
                if ($overdueId) {
                    Customer::batchUpdateStatus($customerIds, $overdueId);
                }
                break;

            case 'sendEmail':
                Customer::batchSendEmail($customerIds);
                break;

            case 'export':
                // $this->exportCustomers($customerIds);
                break;
        }

        // Redirect to avoid form re-submission
        $this->redirect('/customers');
    }

    /**
     * Renders a specified view with data, using output buffering.
     * The layout file (Staff.php) will eventually echo $content.
     *
     * @param  string  $viewPath  Relative path to the view (e.g. "Customers/AllCustomersView.php")
     * @param  array   $data      Key-value data for extraction in the view
     * @return void
     */
    private function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../Views/{$viewPath}";
        $content = ob_get_clean();

        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Simple helper to do an HTTP redirect and then exit.
     *
     * @param  string $url
     * @return never
     */
    private function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Renders a single customer’s detail page.
     * Expects a GET parameter 'id' with the customer's ID.
     *
     * @return void
     */
    public function renderSingleCustomer(): void
    {
        // Retrieve the customer ID from GET parameters
        $customerId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($customerId <= 0) {
            echo "Invalid customer ID.";
            exit;
        }

        // Fetch the customer record
        $customer = Customer::getCustomerById($customerId);
        if (!$customer) {
            echo "Customer not found.";
            exit;
        }

        // Fetch related data
        $addresses  = Customer::getCustomerAddresses($customerId);
        $properties = Customer::getCustomerProperties($customerId);
        $notes      = Customer::getCustomerNotes($customerId);

        // Prepare data for the view
        $data = [
            'customer'   => $customer,
            'addresses'  => $addresses,
            'properties' => $properties,
            'notes'      => $notes,
            'headerIcon' => 'fa-user',
            'headerTitle'=> $customer['display_name'] ?? 'Customer Details',
            'pageTitle'  => $customer['display_name'] ?? 'Customer Details',
        ];

        // Render
        $this->renderView('Customers/SingleCustomerView.php', $data);
    }

    /**
     * Renders the "New Customer" form view.
     *
     * This method prepares any default values if necessary and
     * displays the new-customer form to the user.
     *
     * @return void
     */
    public function renderNewCustomer(): void
    {
        $data = [
            'headerIcon'  => 'fa-user-plus',
            'headerTitle' => 'New Customer',
            'pageTitle'   => 'New Customer',
            // Optionally add default data or other variables
        ];

        $this->renderView('Customers/NewCustomerView.php', $data);
    }

    /**
     * createAction()
     *
     * Handles creation of a new customer record.
     * - If GET, renders the new customer form.
     * - If POST, attempts to create the customer in DB.
     *
     * @return void
     */
    public function createAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Create the customer using the model
                $customerId = Customer::createCustomer($_POST);

                // Example: set a flash message in session for "toast"
                // Make sure session is started somewhere, e.g., bootstrap
                $_SESSION['toast'] = [
                    'type'    => 'success',
                    'message' => 'Customer created successfully.'
                ];

                // Redirect to the newly created customer's view page, focusing on "properties" tab
                $this->redirect("/customers/view?id={$customerId}&tab=properties");
            } catch (Exception $e) {
                // Handle exception (optional: set an error toast)
                echo "Error creating customer: " . $e->getMessage();
                exit;
            }
        } else {
            // If GET request, just render the "New Customer" form
            $this->renderNewCustomer();
        }
    }

    /**
     * exportCustomers()
     *
     * (Optional) Example of a dedicated function for batch export.
     * Currently just a placeholder for generating CSV, PDF, etc.
     *
     * @param  array $customerIds
     * @return void
     */
    private function exportCustomers(array $customerIds): void
    {
        // 1) fetch customer data from the model
        // 2) build CSV or PDF
        // 3) output or force download
        // ...
    }
}
