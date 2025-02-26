<?php

namespace App\Controllers\Finance;
use App\Models\Category;

class CategoryController
{
    public function search()
    {
        try {
            $term = isset($_GET['term']) ? $_GET['term'] : '';
            $categories = Category::search($term);
            $suggestions = [];
            foreach ($categories as $cat) {
                $suggestions[] = [
                    'id'    => $cat->id,
                    'label' => $cat->name,
                    'value' => $cat->name
                ];
            }
            header('Content-Type: application/json');
            echo json_encode($suggestions);
            exit;
        } catch (\Exception $e) {
            error_log("Error in CategoryController::search: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error']);
            exit;
        }
    }
}
?>
