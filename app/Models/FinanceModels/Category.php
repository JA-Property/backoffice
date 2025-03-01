<?php
namespace App\Models\FinanceModels;

class Category
{
    public $id;
    public $name;

    public static function search($term)
    {
        // Assuming you have a Database class to get a PDO connection
        $db = \App\Database::connect();
        $sql = "SELECT id, name FROM expense_categories WHERE name LIKE :term LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute(['term' => '%' . $term . '%']);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cat = new self();
            $cat->id   = $row['id'];
            $cat->name = $row['name'];
            $results[] = $cat;
        }
        return $results;
    }
}
?>
