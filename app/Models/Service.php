<?php
// app/Models/Service.php

namespace App\Models;

use PDO;
use App\Database;  // Assumes you have a Database class to manage the PDO connection

class Service
{
    public $service_id;
    public $property_id;
    public $service_type;
    public $property_address;
    public $service_description;
    public $managed_service_notes;
    public $status;
    public $created_at;
    public $updated_at;

    // Save the service record (insert/update)
    public function save()
    {
        $db = Database::getConnection();

        if (isset($this->service_id)) {
            // Update existing service
            $stmt = $db->prepare("UPDATE services SET property_id = ?, service_type = ?, property_address = ?, service_description = ?, managed_service_notes = ?, status = ?, updated_at = NOW() WHERE service_id = ?");
            return $stmt->execute([
                $this->property_id,
                $this->service_type,
                $this->property_address,
                $this->service_description,
                $this->managed_service_notes,
                $this->status,
                $this->service_id
            ]);
        } else {
            // Insert new service
            $stmt = $db->prepare("INSERT INTO services (property_id, service_type, property_address, service_description, managed_service_notes, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $result = $stmt->execute([
                $this->property_id,
                $this->service_type,
                $this->property_address,
                $this->service_description,
                $this->managed_service_notes,
                $this->status
            ]);
            if ($result) {
                $this->service_id = $db->lastInsertId();
            }
            return $result;
        }
    }
}
