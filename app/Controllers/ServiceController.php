<?php
// app/Controllers/ServiceController.php

namespace App\Controllers;

use App\Models\Service;

class ServiceController
{
    // Render the New Service Wizard view
    public function new()
    {

        $headerIcon  = 'fa-home';
        $headerTitle = $property['addrLine1'] ?? 'Property Details';
        $pageTitle   = 'New Service';

        include __DIR__ . '/../Views/Services/NewServiceEntryView.php';

    }

    // Handle the form submission from the wizard
    public function create()
    {
        // Collect form data – adjust as necessary for your multi-step data
        $serviceType       = $_POST['service_type'] ?? '';
        $propertyId        = $_POST['property_id'] ?? null;
        $propertyAddress   = $_POST['property_address'] ?? '';
        $serviceDescription = $_POST['service_description'] ?? '';
        $managedServiceNotes= $_POST['managed_service_notes'] ?? '';
        $status            = 'pending';

        // Create a new service record
        $service = new Service();
        $service->property_id        = $propertyId;
        $service->service_type       = $serviceType;
        $service->property_address   = $propertyAddress;
        $service->service_description= $serviceDescription;
        $service->managed_service_notes = $managedServiceNotes;
        $service->status             = $status;

        if ($service->save()) {
            // Redirect to the property view page after successful creation
            header('Location: /properties/view?id=' . $propertyId);
            exit;
        } else {
            echo "An error occurred while creating the service.";
        }
    }
}
