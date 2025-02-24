<?php
namespace App\Models;

use PDO;
use PDOException;

class Customer
{
    /**
     * Get a paginated list of customers with optional search, status filter, and sorting.
     *
     * @param string|null $searchTerm   The text to search in name/email, etc.
     * @param string|null $statusFilter e.g. "Active", "Inactive", "Overdue" or null for all.
     * @param string|null $sortColumn   e.g. "name", "balance", "propertyCount", etc.
     * @param string      $sortOrder    e.g. "ASC" or "DESC".
     * @param int         $offset       Starting row for pagination.
     * @param int         $limit        How many rows to fetch.
     *
     * @return array An array of customers, each with property_count included.
     */
    public static function getCustomers(
        ?string $searchTerm,
        ?string $statusFilter,
        ?string $sortColumn,
        string  $sortOrder,
        int     $offset,
        int     $limit
    ): array {
        $pdo = static::db();

        // Build base query
        // We'll left join a subquery that counts properties for each customer.
        // (Assuming `properties` has a customer_id)
        $sql = "
            SELECT c.customer_id,
                   c.display_name AS name,
                   CONCAT(c.primary_first_name, ' ', c.primary_last_name) AS contact_name,
                   c.primary_email AS email,
                   c.primary_phone AS phone,
                   cs.status_name  AS status,
                   cs.status_color,
                   cs.status_icon,
                   -- For demonstration, assume there's a 'balance' column in 'customers'
                   c.balance,
                   IFNULL(propCounts.property_count, 0) AS propertyCount
            FROM customers c
            LEFT JOIN customer_status cs ON c.status_id = cs.status_id
            LEFT JOIN (
                SELECT customer_id, COUNT(*) AS property_count
                FROM properties
                GROUP BY customer_id
            ) AS propCounts ON c.customer_id = propCounts.customer_id
            WHERE 1=1
        ";

        // Add optional filters
        $params = [];
        if (!empty($searchTerm)) {
            $sql .= " AND (
                c.display_name LIKE :search
                OR c.primary_first_name LIKE :search
                OR c.primary_last_name LIKE :search
                OR c.primary_email LIKE :search
            )";
            $params[':search'] = '%' . $searchTerm . '%';
        }
        if (!empty($statusFilter)) {
            // We'll filter by the status_name in the joined table
            $sql .= " AND cs.status_name = :statusFilter";
            $params[':statusFilter'] = $statusFilter;
        }

        // Sorting
        // To avoid direct injection, we whittle down allowed sort columns
        $allowedSortCols = ['name', 'propertyCount', 'phone', 'email', 'status', 'balance'];
        $orderByCol = in_array($sortColumn, $allowedSortCols) ? $sortColumn : 'name';

        $orderByDir = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $sql .= " ORDER BY $orderByCol $orderByDir";

        // Limit/offset
        $sql .= " LIMIT :offset, :limit";

        $stmt = $pdo->prepare($sql);

        // Bind the limit/offset properly
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);

        // Bind search params if set
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get the total count of customers matching optional search and status filter
     * (used for pagination).
     */
    public static function getCustomersCount(?string $searchTerm, ?string $statusFilter): int
    {
        $pdo = static::db();
        $sql = "
            SELECT COUNT(*) as cnt
            FROM customers c
            LEFT JOIN customer_status cs ON c.status_id = cs.status_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND (
                c.display_name LIKE :search
                OR c.primary_first_name LIKE :search
                OR c.primary_last_name LIKE :search
                OR c.primary_email LIKE :search
            )";
            $params[':search'] = '%' . $searchTerm . '%';
        }
        if (!empty($statusFilter)) {
            $sql .= " AND cs.status_name = :statusFilter";
            $params[':statusFilter'] = $statusFilter;
        }

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get sum of all balances (or filtered if needed).
     * For the example, we'll assume the entire table's sum is needed.
     */
    public static function getTotalBalance(): float
    {
        $pdo = static::db();
        $sql = "SELECT SUM(balance) AS total_balance FROM customers";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row && $row['total_balance'] ? (float)$row['total_balance'] : 0.0;
    }

    /**
     * Update the status_id for multiple customers at once (batch action).
     * If you do "Mark as Overdue," you can find the Overdue status_id and update it.
     *
     * @param array $customerIds
     * @param int   $newStatusId
     * @return int  Number of rows updated
     */
    public static function batchUpdateStatus(array $customerIds, int $newStatusId): int
    {
        if (empty($customerIds)) {
            return 0;
        }
        $pdo = static::db();

        // Build placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($customerIds), '?'));

        $sql = "UPDATE customers SET status_id = :status_id
                WHERE customer_id IN ($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status_id', $newStatusId, PDO::PARAM_INT);

        // Bind each ID
        $i = 1;
        foreach ($customerIds as $id) {
            $stmt->bindValue($i, $id, PDO::PARAM_INT);
            $i++;
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Example method to handle "send email" or "export" in batch (pseudo-code).
     * In real code, you might do the emailing or exporting here.
     *
     * @param array $customerIds
     */
    public static function batchSendEmail(array $customerIds): void
    {
        // Implementation depends on your mailer library, etc.
        // For now, do nothing or just imagine we send them
    }

    /**
     * Example method to retrieve the Overdue status_id by name (if needed).
     */
    public static function getStatusIdByName(string $statusName): ?int
    {
        $pdo = static::db();
        $stmt = $pdo->prepare("SELECT status_id FROM customer_status WHERE status_name = :name LIMIT 1");
        $stmt->execute([':name' => $statusName]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int)$row['status_id'] : null;
    }

     /**
     * Get a single customer record by ID.
     *
     * @param int $customerId
     * @return array|null
     */
    public static function getCustomerById(int $customerId): ?array
    {
        $pdo = static::db();
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = :id LIMIT 1");
        $stmt->execute([':id' => $customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $customer ?: null;
    }

    /**
     * Get addresses for a given customer.
     *
     * @param int $customerId
     * @return array
     */
    public static function getCustomerAddresses(int $customerId): array
    {
        $pdo = static::db();
        $stmt = $pdo->prepare("SELECT * FROM addresses WHERE customer_id = :id");
        $stmt->execute([':id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get properties for a given customer.
     *
     * @param int $customerId
     * @return array
     */
    public static function getCustomerProperties(int $customerId): array
    {
        $pdo = static::db();
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE customer_id = :id");
        $stmt->execute([':id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get customer notes for a given customer.
     *
     * @param int $customerId
     * @return array
     */
    public static function getCustomerNotes(int $customerId): array
    {
        $pdo = static::db();
        // Assuming notes are stored in a table called customer_notes.
        $stmt = $pdo->prepare("SELECT * FROM customer_notes WHERE customer_id = :id ORDER BY created_at DESC");
        $stmt->execute([':id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
 * Find a single customer record by its ID.
 *
 * @param int $customerId
 * @return array|null
 */
public static function find(int $customerId): ?array
{
    $pdo = static::db();
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = :id LIMIT 1");
    $stmt->execute([':id' => $customerId]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    return $customer ?: null;
}


    /**
     * Helper to get PDO connection.
     * Adjust this to your own DB class or singleton, or inject it.
     */
    public static function db(): PDO
    {
        // This is a placeholder for however you manage DB connections.
        // For example:
        // return Database::getConnection();
        static $pdo;
        if (!$pdo) {
            try {
                $dsn = 'mysql:host=localhost;dbname=fdsa;charset=utf8mb4';
                $user = 'root';
                $pass = '';
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
                $pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return $pdo;
    }
}
