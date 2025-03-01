<?php
namespace App\Models\FinanceModels;

class Expense
{
    public $id;
    public $expense_date;
    public $category;
    public $vendor;
    public $amount;
    public $payment_method;
    public $notes;
    public $receipt;

    /**
     * Inserts a new expense into the submitted_expenses table.
     *
     * @param array $data Array with keys:
     *  - expense_date, category, vendor, amount, payment_method, notes, receipt
     * @return int|false Returns the inserted expense ID on success, or false on failure.
     */
    public static function create($data)
    {
        $db = \App\Database::connect();
        $sql = "INSERT INTO submitted_expenses 
                (expense_date, category, vendor, amount, payment_method, notes, receipt, created_at, updated_at)
                VALUES (:expense_date, :category, :vendor, :amount, :payment_method, :notes, :receipt, NOW(), NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':expense_date', $data['expense_date']);
        $stmt->bindValue(':category', $data['category']);
        $stmt->bindValue(':vendor', $data['vendor']);
        $stmt->bindValue(':amount', $data['amount']);
        $stmt->bindValue(':payment_method', $data['payment_method']);
        $stmt->bindValue(':notes', $data['notes']);
        $stmt->bindValue(':receipt', $data['receipt']);
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
 * Retrieves a single expense record by its ID.
 *
 * @param int $id The ID of the expense.
 * @return array|null Returns an associative array of the expense data, or null if not found.
 */
public static function getExpenseById($id)
{
    $db = \App\Database::connect();
    $sql = "SELECT * FROM submitted_expenses WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
    $expense = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $expense ?: null;
}

}
