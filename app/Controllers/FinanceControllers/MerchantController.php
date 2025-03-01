<?php
namespace App\Controllers\FinanceControllers;

use App\Models\FinanceModels\Merchant;

class MerchantController
{
    /**
     * Returns JSON suggestions for merchant names based on a search term.
     */
    public function search()
    {
        try {
            $term = isset($_GET['term']) ? $_GET['term'] : '';
            $merchants = Merchant::search($term);
            $suggestions = [];
            foreach ($merchants as $merchant) {
                $suggestions[] = [
                    'id'    => $merchant->id,
                    'label' => $merchant->name,
                    'value' => $merchant->name
                ];
            }
            header('Content-Type: application/json');
            echo json_encode($suggestions);
            exit;
        } catch (\Exception $e) {
            error_log("Error in MerchantController::search: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error']);
            exit;
        }
    }
}
