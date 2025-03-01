<?php
namespace App\Controllers\FinanceControllers;

use App\Models\FinanceModels\ExpensePaymentMethod;
use App\Models\FinanceModels\PaymentMethod;

class ExpensePaymentMethodController
{
    /**
     * Returns JSON of all payment methods.
     */
    public function getAll()
    {
        try {
            $methods = ExpensePaymentMethod::getAll();
            $result = [];
            foreach ($methods as $method) {
                $result[] = [
                    'id'   => $method->id,
                    'name' => $method->name
                ];
            }
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } catch (\Exception $e) {
            error_log("Error in PaymentMethodController::getAll: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error']);
            exit;
        }
    }
}
