<?php
namespace App\Models;

use PDO;
use App\Database;

class Property
{
    /**
     * Create a new property record.
     *
     * @param array $data Form data from the "Add Property" submission.
     * @return int The new property_id.
     * @throws \Exception if the insert fails.
     */
    public static function createProperty(array $data): int
    {
        $pdo = self::db();
        $pdo->beginTransaction();

        try {
            $sql = "INSERT INTO properties (
                        customer_id,
                        addrLine1,
                        addrLine2,
                        city,
                        state,
                        zip,
                        type,
                        notes,
                        created_at
                    ) VALUES (
                        :customer_id,
                        :addrLine1,
                        :addrLine2,
                        :city,
                        :state,
                        :zip,
                        :type,
                        :notes,
                        NOW()
                    )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':customer_id' => $data['customer_id'] ?? null,
                ':addrLine1'   => $data['addrLine1']   ?? '',
                ':addrLine2'   => $data['addrLine2']   ?? '',
                ':city'        => $data['city']        ?? '',
                ':state'       => $data['state']       ?? '',
                ':zip'         => $data['zip']         ?? '',
                ':type'        => $data['type']        ?? 'Residential',
                ':notes'       => $data['notes']       ?? ''
            ]);
            $propertyId = (int)$pdo->lastInsertId();
            $pdo->commit();
            return $propertyId;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Get a single property by its ID.
     */
    public static function getPropertyById(int $propertyId): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE property_id = :id LIMIT 1");
        $stmt->execute([':id' => $propertyId]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        return $property ?: null;
    }

    /**
     * Get all properties for a specific customer.
     */
    public static function getPropertiesByCustomerId(int $customerId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE customer_id = :customer_id");
        $stmt->execute([':customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get a PDO instance via App\Database.
     */
    protected static function db(): PDO
    {
        return Database::connect();
    }
}
