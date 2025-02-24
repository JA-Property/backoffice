<?php
namespace App\Controllers;

// Suppose your Customer.php is at C:\xampp\htdocs\app\Models\Customer.php
require_once __DIR__ . '/../Models/Customer.php';

use App\Models\Customer;

// Now the controller referencing App\Models\Customer can find the class


class CustomerController
{
    /**
     * Display the "All Customers" page:
     *  - Search & filter
     *  - Sorting
     *  - Pagination
     *  - Quick stats (total count, total balance)
     *  - Optional batch actions
     */
    public function renderAllCustomers()
    {
        // 1) Gather input from $_GET or $_POST
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : null;
        $statusFilter = isset($_GET['status']) ? trim($_GET['status']) : null;

        // Sorting
        $sortColumn = isset($_GET['sort']) ? trim($_GET['sort']) : 'name';
        $sortOrder = isset($_GET['order']) ? strtoupper($_GET['order']) : 'ASC';

        // Pagination
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pageSize = isset($_GET['pageSize']) ? (int) $_GET['pageSize'] : 10;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $pageSize;

        // 2) If there’s a request for batch actions, handle that
        if (!empty($_POST['batchAction']) && !empty($_POST['customerIds'])) {
            $customerIds = array_map('intval', $_POST['customerIds']);
            $action = $_POST['batchAction'];

            switch ($action) {
                case 'markOverdue':
                    // Suppose we want to set them to Overdue
                    $overdueId = Customer::getStatusIdByName('Overdue');
                    if ($overdueId) {
                        Customer::batchUpdateStatus($customerIds, $overdueId);
                    }
                    break;

                case 'sendEmail':
                    // Example: call a method to send email
                    Customer::batchSendEmail($customerIds);
                    break;

                case 'export':
                    // Example: you might generate a CSV or PDF
                    // We'll do a pseudo-code approach:
                    // $this->exportCustomers($customerIds);
                    break;
            }

            // After batch action, redirect to avoid form re-submission
            header('Location: /customers');
            exit;
        }

        // 3) Get data from the Model
        //    a) All customers matching search/status, sorted, limited
        $customers = Customer::getCustomers(
            $searchTerm,
            $statusFilter,
            $sortColumn,
            $sortOrder,
            $offset,
            $pageSize
        );

        //    b) Total count of matching rows (for pagination)
        $totalCount = Customer::getCustomersCount($searchTerm, $statusFilter);

        //    c) Sum of all balances
        $totalBalance = Customer::getTotalBalance();

        // 4) Calculate how many pages we have in total
        $totalPages = max(1, ceil($totalCount / $pageSize));

        // 5) Render the "All Customers" view
        // You can pass data to the view in multiple ways.
        // If you're using a simple PHP approach, you might do:
        $headerIcon = 'fa-users';
        $headerTitle = 'All Customers';
        $pageTitle = 'All Customers';

        // We'll store these in variables that your view might expect
        $totalCustomers = $totalCount; // for the quick stat
        // The code that sets #totalCustomers / #totalBalance in the HTML
        // could be replaced or set via partial injection, etc.

        // Now we can load the same view you showed in your snippet:
        // We'll do a simple approach: define local variables, then include the view.
        // The final snippet is effectively a layout that merges these variables.
        // Because your snippet uses $content, we might do:
        ob_start();
        include __DIR__ . '/../Views/Customers/AllCustomersView.php';
        $content = ob_get_clean();

        include __DIR__ . '/../Views/Layouts/Staff.php';

    }

    /**
     * Render the Single Customer View.
     * Expects a GET parameter 'id' with the customer's ID.
     */
    public function renderSingleCustomer()
    {
        // Retrieve the customer ID from GET parameters.
        $customerId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($customerId <= 0) {
            echo "Invalid customer ID.";
            exit;
        }

        // Get the customer record.
        $customer = Customer::getCustomerById($customerId);
        if (!$customer) {
            echo "Customer not found.";
            exit;
        }

        // Get related data.
        $addresses = Customer::getCustomerAddresses($customerId);
        $properties = Customer::getCustomerProperties($customerId);
        $notes = Customer::getCustomerNotes($customerId);

        // Set header and page titles.
        $headerIcon = 'fa-user';
        $headerTitle = $customer['display_name'] ?? 'Customer Details';
        $pageTitle = $headerTitle;

        // Make the data available to the view.
        // (You might use a templating engine in a full framework.)
        // Here we simply set variables that the view will use.
        // For example:
        //   $customer, $addresses, $properties, $notes

        // Capture the view output.
        ob_start();
        include __DIR__ . '/../Views/Customers/SingleCustomerView.php';
        $content = ob_get_clean();

        // Include the layout file which echoes $content.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

     /**
     * Render the New Customer Form view.
     *
     * This method prepares any default values if necessary,
     * captures the view output into $content, and then includes
     * the master layout file.
     */
    public function renderNewCustomer()
    {
        // Set header and page titles for the new customer form.
        $headerIcon  = 'fa-user-plus';
        $headerTitle = 'New Customer';
        $pageTitle   = 'New Customer';

        // (Optional) Prepare any default values to prefill the form.
        // For example, you could load a list of referral sources, etc.
        // $defaultData = [ ... ];

        // Capture the view output.
        ob_start();
        include __DIR__ . '/../Views/Customers/NewCustomerView.php';
        $content = ob_get_clean();

        // Include the master layout file, which will echo $content in the correct spot.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }
    /**
     * (Optional) Example for a dedicated "export" function for batch actions.
     */
    private function exportCustomers(array $customerIds)
    {
        $rows = []; // fetch them from model, then create a CSV, etc.
        // ...
    }
}
