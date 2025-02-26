<?php
namespace App\Controllers;

use App\Models\Journal;
use App\Models\Expense;

class FinanceController
{
    /**
     * Display the "New Journal Entry" form.
     *
     * This method prepares any necessary data (for example, a list of accounts),
     * captures the output of the new journal entry view, and then includes the master layout.
     */
    public function newJournalEntry()
    {
        // Optionally, gather data needed for the form (e.g., available accounts)
        // $accounts = Journal::getAccounts();

        // Set header and page titles for the new journal entry form.
        $headerIcon  = 'fa-book';
        $headerTitle = 'New Journal Entry';
        $pageTitle   = 'New Journal Entry';

        // Capture the view output.
        ob_start();
        include __DIR__ . '/../Views/Finance/NewJournalEntryView.php';
        $content = ob_get_clean();

        // Include the master layout that uses $content.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Display the list or details of journal entries.
     *
     * This method retrieves journal entry data (if necessary), prepares the view,
     * and wraps it in the master layout.
     */
    public function viewJournalEntry()
    {
        // Retrieve journal entries.
        // For example, you might filter or paginate entries via GET parameters.
        // Here, we use a placeholder; replace it with actual retrieval logic.
        $journals = Journal::getAllEntries();  // Ensure this method exists in your Journal model

        // Set header and page titles for the journal entries view.
        $headerIcon  = 'fa-list';
        $headerTitle = 'Journal Entries';
        $pageTitle   = 'Journal Entries';

        // Make the $journals data available to the view.
        ob_start();
        include __DIR__ . '/../Views/Finance/JournalEntryView.php';
        $content = ob_get_clean();

        // Include the master layout.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Display the details for an existing expense.
     *
     * This method retrieves a specific expense using an ID provided via GET,
     * then renders a detailed view of that expense.
     */
    public function viewExpense()
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
        include __DIR__ . '/../Views/Finance/ExistingExpenseView.php';
        $content = ob_get_clean();

        // Include the master layout.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Display the "New Expense" form.
     *
     * This method prepares any necessary data (for example, expense categories),
     * captures the new expense view output, and then includes the master layout.
     */
    public function newExpense()
    {
        // Optionally, gather data for the form (e.g., expense categories)
        // $categories = Expense::getExpenseCategories();

        // Set header and page titles for the new expense form.
        $headerIcon  = 'fa-plus-circle';
        $headerTitle = 'New Expense';
        $pageTitle   = 'New Expense';

        // Capture the view output.
        ob_start();
        include __DIR__ . '/../Views/Finance/FieldTechNewExpenseView.php';
        $content = ob_get_clean();

        // Include the master layout.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Display the mobile-specific "New Expense" form.
     *
     * This method loads a mobile-optimized view for creating a new expense and uses a mobile-specific layout.
     */
    public function mobileNewExpense()
    {
        // Optionally, gather mobile-specific data if needed.

        // Set header and page titles for the mobile expense form.
        $headerIcon  = 'fa-mobile';
        $headerTitle = 'New Expense (Mobile)';
        $pageTitle   = 'New Expense (Mobile)';

        // Capture the mobile view output.
        ob_start();
        include __DIR__ . '/../Views/Mobile/NewExpense.php';
        $content = ob_get_clean();

        // Include the mobile-specific layout.
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }
}
?>
