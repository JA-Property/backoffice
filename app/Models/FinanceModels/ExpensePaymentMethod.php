<?php
namespace App\Models\FinanceModels;

class ExpensePaymentMethod
{
    public $id;
    public $name;

    /**
     * Retrieve all payment methods from the database.
     *
     * @return array An array of PaymentMethod objects.
     */
    public static function getAll()
    {
        $db = \App\Database::connect();
        $sql = "SELECT id, name FROM expense_payment_methods ORDER BY name ASC";
        $stmt = $db->query($sql);
        $methods = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $method = new self();
            $method->id = $row['id'];
            $method->name = $row['name'];
            $methods[] = $method;
        }
        return $methods;
    }
}
