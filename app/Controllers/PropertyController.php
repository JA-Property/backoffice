<?php
namespace App\Controllers;

use App\Models\Property;

class PropertyController
{
    /**
     * Render the "New Property" form.
     */
    public function renderNewProperty()
    {
        $headerIcon  = 'fa-home';
        $headerTitle = 'New Property';
        $pageTitle   = 'New Property';

        // You may also pass default data (for example, a list of property types) if needed.
        ob_start();
        include __DIR__ . '/../Views/Properties/NewPropertyView.php';
        $content = ob_get_clean();

        include __DIR__ . '/../Views/Layouts/Staff.php';
    }

    /**
     * Process the form submission to create a new property.
     */
    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Create the property record via the model.
                $propertyId = Property::createProperty($_POST);

                // Set a flash message.
                $_SESSION['toast'] = [
                    'type'    => 'success',
                    'message' => 'Property created successfully.'
                ];

                // Redirect back to the customer's view page, with the properties tab active.
                $customerId = $_POST['customer_id'] ?? 0;
                header("Location: /customers/view?id={$customerId}&tab=properties");
                exit;
            } catch (\Exception $e) {
                // Handle errors (set error flash message or display error).
                echo "Error creating property: " . $e->getMessage();
                exit;
            }
        } else {
            // If GET, render the "New Property" form.
            $this->renderNewProperty();
        }
    }

    /**
     * Render a single property's detail view.
     */
    public function renderProperty()
    {
        $propertyId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($propertyId <= 0) {
            echo "Invalid property ID.";
            exit;
        }

        $property = Property::getPropertyById($propertyId);
        if (!$property) {
            echo "Property not found.";
            exit;
        }

        $headerIcon  = 'fa-home';
        $headerTitle = $property['addrLine1'] ?? 'Property Details';
        $pageTitle   = 'Property Details';

        ob_start();
        include __DIR__ . '/../Views/Properties/SinglePropertyView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/Layouts/Staff.php';
    }
}
