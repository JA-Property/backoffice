<?php
namespace App\Controllers\FinanceControllers;

use App\Models\FinanceModels\Expense;

class ExpenseController
{
    

        /**
     * Display the details for an existing expense.
     *
     * This method retrieves a specific expense using an ID provided via GET,
     * then renders a detailed view of that expense.
     */
    public function renderViewExpenseView()
    {
        // Retrieve the expense ID from GET parameters.
        $expenseId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($expenseId <= 0) {
            echo "Invalid expense ID.";
            exit;
        }

        // Retrieve expense data from the model.
        $expense = Expense::getExpenseById($expenseId);
        if (!$expense) {
            echo "Expense not found.";
            exit;
        }

        // Set header and page titles for the expense detail view.
        $headerIcon  = 'fa-file-invoice';
        $headerTitle = 'Expense Details';
        $pageTitle   = 'Expense Details';

        // Make the $expense data available to the view.
        ob_start();
        include __DIR__ . '/../../Views/Finance/ExistingExpense.php';
        $content = ob_get_clean();

        // Include the master layout.
        include __DIR__ . '/../../Views/Layouts/Staff.php';
    }

    /**
     * Display the "New Expense" form.
     *
     * This method prepares any necessary data (for example, expense categories),
     * captures the new expense view output, and then includes the master layout.
     */
    public function renderNewExpenseView()
    {
        // Optionally, gather data for the form (e.g., expense categories)
        // $categories = Expense::getExpenseCategories();

        // Set header and page titles for the new expense form.
        $headerIcon  = 'fa-plus-circle';
        $headerTitle = 'New Expense';
        $pageTitle   = 'New Expense';

        // Capture the view output.
        ob_start();
        include __DIR__ . '/../../Views/Finance/NewExpense.php';
        $content = ob_get_clean();

        // Include the master layout.
        include __DIR__ . '/../../Views/Layouts/Staff.php';
    }


/**
 * Handles the submission of a new expense.
 * Expects a POST request with fields:
 * - expenseDate
 * - expenseCategory
 * - expenseVendor
 * - expenseAmount
 * - expensePaymentMethod
 * - expenseNotes
 * - receiptUpload (file)
 */
public function submitExpense()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "Method Not Allowed";
        exit;
    }
    
    // Collect POST data
    $expense_date    = isset($_POST['expenseDate']) ? $_POST['expenseDate'] : null;
    $category        = isset($_POST['expenseCategory']) ? $_POST['expenseCategory'] : null;
    $vendor          = isset($_POST['expenseVendor']) ? $_POST['expenseVendor'] : null;
    $amount          = isset($_POST['expenseAmount']) ? $_POST['expenseAmount'] : null;
    $payment_method  = isset($_POST['expensePaymentMethod']) ? $_POST['expensePaymentMethod'] : null;
    $notes           = isset($_POST['expenseNotes']) ? $_POST['expenseNotes'] : null;
    
    // Process receipt upload (if provided)
    $receipt_path = null;
    if (isset($_FILES['receiptUpload']) && $_FILES['receiptUpload']['error'] === UPLOAD_ERR_OK) {
        // Define directory for uploads (adjust path as needed)
        $uploadDir = __DIR__ . '/../../uploads/receipts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileTmpPath = $_FILES['receiptUpload']['tmp_name'];
        $originalName = basename($_FILES['receiptUpload']['name']);
        $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Generate a unique file name
        $newFileName = uniqid('receipt_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;
        
        // Move the file
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Store relative path (or absolute path, depending on your preference)
            $receipt_path = '/uploads/receipts/' . $newFileName;
        } else {
            error_log("ExpenseController::submitExpense - Failed to move uploaded file.");
        }
    }
    
    // Prepare data array for the Expense model
    $data = [
        'expense_date'   => $expense_date,
        'category'       => $category,
        'vendor'         => $vendor,
        'amount'         => $amount,
        'payment_method' => $payment_method,
        'notes'          => $notes,
        'receipt'        => $receipt_path,
    ];
    
    // Insert the new expense record
    $newExpenseId = Expense::create($data);
    if ($newExpenseId) {
        // Once the expense is created, create a new unread notification.
        // This assumes you have a 'system_notifications' table with columns:
        // id, user_id, title, message, is_read, created_at, updated_at
        try {
            $db = \App\Database::connect();

            // Example notification content — adjust to your actual columns and data
            $notificationSql = "
                INSERT INTO system_notifications 
                    (user_id, title, message, is_read, created_at, updated_at) 
                VALUES 
                    (:user_id, :title, :message, 0, NOW(), NOW())
            ";
            $stmtNotify = $db->prepare($notificationSql);

            // Use whichever user ID or data you have in session
            // (Replace 'id' with the actual key for your logged-in user)
            $stmtNotify->bindValue(':user_id', $_SESSION['user']['id'] ?? 1);

            // Notification title/message can be anything meaningful
            $stmtNotify->bindValue(':title',   'New Expense Submitted');
            $stmtNotify->bindValue(':message', 'Expense #'.$newExpenseId.' was submitted by '.$_SESSION['user']['display_name']);

            $stmtNotify->execute();
        } catch (\PDOException $e) {
            error_log('Failed to create notification: ' . $e->getMessage());
        }

        // Redirect to the expense details page
        header("Location: /finance/expense/view?id=" . $newExpenseId);
        exit;
    } else {
        echo "Failed to submit expense.";
    }
}

}
