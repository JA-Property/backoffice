<?php
namespace App\Controllers;

use App\Models\Journal;
use App\Models\FinanceModels\Expense;

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




}
?>
