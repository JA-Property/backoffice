<?php
/**
 * /app/Models/Customer.php
 *
 * This file defines the Customer model, responsible for database operations
 * related to customers. It includes methods for fetching single-customer data,
 * listing/paginating customers, searching, counting, creating new records,
 * fetching addresses/properties/notes, and more.
 *
 * Copyright:
 *  (c) 2025 JA Property Management LLC. All rights reserved.
 *  Confidential & Proprietary
 *
 * @package   App\Models
 */

namespace App\Models;

use PDO;
use App\Database;
use Exception;

/**
 * Class Customer
 *
 * Provides static methods to interact with the 'customers' table
 * and related tables (emails, phones, addresses, properties, notes).
 */
class Customer
{
    /**
     * Returns a PDO instance using environment-based DB connection.
     * Utilizes App\Database::connect().
     *
     * @return PDO
     */
    protected static function db(): PDO
    {
        return Database::connect();
    }

    /**
     * Alias for getCustomerById().
     * 
     * @param  int  $customerId
     * @return array|null
     */
    public static function find(int $customerId): ?array
    {
        return self::getCustomerById($customerId);
    }

    /**
     * getCustomerById()
     *
     * Fetches a single customer record by ID, joining sub-data for
     * primary email, phone, and billing address (plus counts).
     *
     * @param  int  $customerId
     * @return array|null  Returns the combined row or null if not found
     */
    public static function getCustomerById(int $customerId): ?array
    {
        $pdo = self::db();

        // Sub-query to bring in:
        //  - primary_email + email_count
        //  - primary_phone + phone_count
        //  - primary_billing + billing_count
        // Then selecting from `customers` with these sub-joined columns.
        $sql = "
            SELECT
                c.*,

                e.primary_email,
                e.email_count,
                ph.primary_phone,
                ph.phone_count,
                b.primary_billing,
                b.billing_count

            FROM customers c

            -- Sub-join for primary email and total email_count
            LEFT JOIN (
                SELECT
                    ce.customer_id,
                    ce.email_address AS primary_email,
                    (
                        SELECT COUNT(*) 
                        FROM customer_emails 
                        WHERE customer_id = ce.customer_id
                    ) AS email_count
                FROM customer_emails ce
                WHERE ce.is_primary = 1
            ) AS e ON c.customer_id = e.customer_id

            -- Sub-join for primary phone and total phone_count
            LEFT JOIN (
                SELECT
                    cp.customer_id,
                    cp.phone_number AS primary_phone,
                    (
                        SELECT COUNT(*) 
                        FROM customer_phones 
                        WHERE customer_id = cp.customer_id
                    ) AS phone_count
                FROM customer_phones cp
                WHERE cp.is_primary = 1
            ) AS ph ON c.customer_id = ph.customer_id

            -- Sub-join for primary billing address + count of all billing addresses
            LEFT JOIN (
                SELECT
                    ca.customer_id,
                    CONCAT(
                        IFNULL(ca.address_line1, ''), 
                        CASE WHEN ca.address_line2 != '' THEN CONCAT(', ', ca.address_line2) ELSE '' END,
                        CASE WHEN ca.city != ''        THEN CONCAT(', ', ca.city)        ELSE '' END,
                        CASE WHEN ca.state_province != '' THEN CONCAT(', ', ca.state_province) ELSE '' END,
                        CASE WHEN ca.postal_code != '' THEN CONCAT(' ', ca.postal_code)  ELSE '' END
                    ) AS primary_billing,
                    (
                        SELECT COUNT(*) 
                        FROM customer_addresses
                        WHERE address_type = 'Billing'
                          AND customer_id = ca.customer_id
                    ) AS billing_count
                FROM customer_addresses ca
                WHERE ca.is_primary = 1
                  AND ca.address_type = 'Billing'
            ) AS b ON c.customer_id = b.customer_id

            WHERE c.customer_id = :id
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        return $customer ?: null;
    }

    /**
     * getCustomers()
     *
     * Returns a list of customers with optional search, status filter,
     * sorting, and pagination. Also joins sub-data:
     *  - primary email (if is_primary=1)
     *  - primary phone
     *  - property_count
     *
     * @param  string|null  $searchTerm    Search text for display_name, first_name, last_name, or email
     * @param  string|null  $statusFilter  If the customers table has a status_id or similar
     * @param  string|null  $sortColumn    e.g. "name", "propertyCount", "phone", etc.
     * @param  string       $sortOrder     "ASC" or "DESC"
     * @param  int          $offset        For pagination: start row
     * @param  int          $limit         For pagination: number of rows to fetch
     * @return array        List of matching customer rows
     */
    public static function getCustomers(
        ?string $searchTerm,
        ?string $statusFilter,
        ?string $sortColumn,
        string  $sortOrder,
        int     $offset,
        int     $limit
    ): array {
        $pdo = self::db();

        // Base query
        $sql = "
            SELECT
                c.customer_id,
                c.display_name AS name,
                CONCAT(c.first_name, ' ', c.last_name) AS contact_name,
                e.primary_email AS email,
                ph.primary_phone AS phone,
                c.opening_balance,
                IFNULL(propCounts.property_count, 0) AS propertyCount,
                c.status_id

            FROM customers c

            LEFT JOIN (
                SELECT customer_id, email_address AS primary_email
                FROM customer_emails
                WHERE is_primary = 1
            ) e ON c.customer_id = e.customer_id

            LEFT JOIN (
                SELECT customer_id, phone_number AS primary_phone
                FROM customer_phones
                WHERE is_primary = 1
            ) ph ON c.customer_id = ph.customer_id

            LEFT JOIN (
                SELECT customer_id, COUNT(*) AS property_count
                FROM properties
                GROUP BY customer_id
            ) AS propCounts ON c.customer_id = propCounts.customer_id

            WHERE 1=1
        ";

        $params = [];

        // Search
        if (!empty($searchTerm)) {
            $sql .= "
                AND (
                    c.display_name     LIKE :search
                    OR c.first_name    LIKE :search
                    OR c.last_name     LIKE :search
                    OR e.primary_email LIKE :search
                )
            ";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        // Status filter
        if (!empty($statusFilter)) {
            $sql .= " AND c.status_id = :statusFilter";
            $params[':statusFilter'] = $statusFilter;
        }

        // Sorting
        // Suppose these are the allowed columns
        $allowedSortCols = ['name', 'propertyCount', 'phone', 'email', 'opening_balance', 'status'];
        $orderByCol      = in_array($sortColumn, $allowedSortCols, true) ? $sortColumn : 'name';
        $orderByDir      = (strtoupper($sortOrder) === 'DESC') ? 'DESC' : 'ASC';

        $sql .= "
            ORDER BY $orderByCol $orderByDir
            LIMIT :offset, :limit
        ";

        $stmt = $pdo->prepare($sql);

        // Bind limit & offset
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);

        // Bind any optional parameters (search, status)
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * getCustomersCount()
     *
     * Counts how many customers match the optional search and status filter.
     * Useful for pagination logic.
     *
     * @param  string|null  $searchTerm
     * @param  string|null  $statusFilter
     * @return int
     */
    public static function getCustomersCount(
        ?string $searchTerm,
        ?string $statusFilter
    ): int {
        $pdo = self::db();

        $sql = "
            SELECT COUNT(*) AS cnt
            FROM customers c
            LEFT JOIN (
                SELECT customer_id, email_address AS primary_email
                FROM customer_emails
                WHERE is_primary = 1
            ) e ON c.customer_id = e.customer_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($searchTerm)) {
            $sql .= "
                AND (
                    c.display_name     LIKE :search
                    OR c.first_name    LIKE :search
                    OR c.last_name     LIKE :search
                    OR e.primary_email LIKE :search
                )
            ";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if (!empty($statusFilter)) {
            $sql .= " AND c.status_id = :statusFilter";
            $params[':statusFilter'] = $statusFilter;
        }

        $stmt = $pdo->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int) $row['cnt'] : 0;
    }

    /**
     * getTotalBalance()
     *
     * Sums all customers' opening_balance columns, returning a float total.
     *
     * @return float
     */
    public static function getTotalBalance(): float
    {
        $pdo = self::db();
        $sql = "SELECT SUM(opening_balance) AS total_balance FROM customers";
        $stmt = $pdo->query($sql);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['total_balance']
            ? (float) $row['total_balance']
            : 0.0;
    }

    /**
     * getCustomerAddresses()
     *
     * Retrieves all addresses for a given customer ID.
     *
     * @param  int   $customerId
     * @return array
     */
    public static function getCustomerAddresses(int $customerId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT *
            FROM customer_addresses
            WHERE customer_id = :id
        ");
        $stmt->execute([':id' => $customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * getCustomerProperties()
     *
     * Retrieves all properties for a given customer ID (if applicable).
     *
     * @param  int   $customerId
     * @return array
     */
    public static function getCustomerProperties(int $customerId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT *
            FROM properties
            WHERE customer_id = :id
        ");
        $stmt->execute([':id' => $customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * getCustomerNotes()
     *
     * Retrieves all notes for a given customer ID (descending by created_at).
     *
     * @param  int   $customerId
     * @return array
     */
    public static function getCustomerNotes(int $customerId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT *
            FROM customer_notes
            WHERE customer_id = :id
            ORDER BY created_at DESC
        ");
        $stmt->execute([':id' => $customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * createCustomer()
     *
     * Creates a new customer record (and optionally inserts primary email, phone, billing address).
     * 
     * Steps:
     *  1) Determine displayName based on the form data (residential, commercial, or other).
     *  2) Insert into the 'customers' table using all columns.
     *  3) Insert primary email/phone if provided.
     *  4) Insert billing address if provided.
     *  5) Commit transaction or rollback on error.
     *
     * @param  array $data The form data (e.g. $_POST).
     * @return int   The new customer_id.
     * @throws Exception If something fails during insert.
     */
    public static function createCustomer(array $data): int
    {
        // For debugging or logs (in production, remove var_dump)
        var_dump($_POST);

        $firstName          = null;
        $lastName           = null;
        $fullyQualifiedName = null;
        $displayName        = null;

        // Determine which fields are filled to set displayName
        if (!empty($data['res_first_name']) || !empty($data['res_last_name'])) {
            // Residential
            $firstName  = $data['res_first_name'] ?? '';
            $lastName   = $data['res_last_name']  ?? '';
            $displayName = ucwords(strtolower(trim($firstName . ' ' . $lastName)));
            $fullyQualifiedName = null;

        } elseif (!empty($data['com_first_name']) || !empty($data['com_last_name'])) {
            // Commercial
            $firstName   = $data['com_first_name'] ?? '';
            $lastName    = $data['com_last_name']  ?? '';
            $fullyQualifiedName = ucwords(strtolower($data['company_name'] ?? ''));
            $displayName        = $fullyQualifiedName;

        } elseif (!empty($data['oth_first_name']) || !empty($data['oth_last_name'])) {
            // Other
            $firstName   = $data['oth_first_name'] ?? '';
            $lastName    = $data['oth_last_name']  ?? '';
            $fullyQualifiedName = ucwords(strtolower($data['organization_name'] ?? ''));
            $displayName        = $fullyQualifiedName;
        }

        $pdo = self::db();
        $pdo->beginTransaction();

        try {
            // 1) Insert into `customers` with all columns from the schema
            $sql = "
                INSERT INTO customers (
                    customer_type,
                    display_name,
                    company_name,
                    title,
                    first_name,
                    middle_name,
                    last_name,
                    suffix,
                    print_name_on_check,
                    org_type,
                    comm_email,
                    comm_phone,
                    comm_sms,
                    preferred_delivery_method,
                    billing_format,
                    invoice_prefix,
                    opening_balance,
                    opening_balance_date,
                    discount_type,
                    discount_amount,
                    default_discount_enabled,
                    terms,
                    tax_exempt,
                    tax_exempt_id,
                    tax_exempt_reason_code,
                    resale_number,
                    referral_source,
                    referral_other_text,
                    notes,
                    qbo_active_flag,
                    qbo_customer_id,
                    qbo_bill_with_parent,
                    qbo_parent_ref,
                    qbo_sync_token,
                    stripe_customer_id,
                    created_by,
                    created_at
                ) VALUES (
                    :customer_type,
                    :display_name,
                    :company_name,
                    :title,
                    :first_name,
                    :middle_name,
                    :last_name,
                    :suffix,
                    :print_name_on_check,
                    :org_type,
                    :comm_email,
                    :comm_phone,
                    :comm_sms,
                    :preferred_delivery_method,
                    :billing_format,
                    :invoice_prefix,
                    :opening_balance,
                    :opening_balance_date,
                    :discount_type,
                    :discount_amount,
                    :default_discount_enabled,
                    :terms,
                    :tax_exempt,
                    :tax_exempt_id,
                    :tax_exempt_reason_code,
                    :resale_number,
                    :referral_source,
                    :referral_other_text,
                    :notes,
                    :qbo_active_flag,
                    :qbo_customer_id,
                    :qbo_bill_with_parent,
                    :qbo_parent_ref,
                    :qbo_sync_token,
                    :stripe_customer_id,
                    :created_by,
                    NOW()
                )
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':customer_type'            => $data['customer_type']             ?? 'Residential',
                ':display_name'             => $displayName                       ?? null,
                ':company_name'             => $fullyQualifiedName                ?? null,
                ':title'                    => $data['title']                     ?? null,
                ':first_name'               => $firstName                         ?? null,
                ':middle_name'              => $data['middle_name']               ?? null,
                ':last_name'                => $lastName                          ?? null,
                ':suffix'                   => $data['suffix']                    ?? null,
                ':print_name_on_check'      => $fullyQualifiedName                ?? null,
                ':org_type'                 => $data['org_type']                  ?? null,
                ':comm_email'               => !empty($data['comm_email'])        ? 1 : 0,
                ':comm_phone'               => !empty($data['comm_phone'])        ? 1 : 0,
                ':comm_sms'                 => !empty($data['comm_sms'])          ? 1 : 0,
                ':preferred_delivery_method'=> $data['preferred_delivery_method'] ?? 'USPS',
                ':billing_format'           => $data['billing_format']            ?? 'Invoice',
                ':invoice_prefix'           => $data['invoice_prefix']            ?? null,
                ':opening_balance'          => $data['opening_balance']           ?? null,
                ':opening_balance_date'     => $data['opening_balance_date']      ?? null,
                ':discount_type'            => $data['discount_type']             ?? null,
                ':discount_amount'          => $data['discount_amount']           ?? null,
                ':default_discount_enabled' => !empty($data['default_discount_enabled']) ? 1 : 0,
                ':terms'                    => $data['terms']                     ?? null,
                ':tax_exempt'               => $data['tax_exempt']                ?? 'none',
                ':tax_exempt_id'            => $data['tax_exempt_id']             ?? null,
                ':tax_exempt_reason_code'   => $data['tax_exempt_reason_code']    ?? null,
                ':resale_number'            => $data['resale_number']             ?? null,
                ':referral_source'          => $data['referral_source']           ?? null,
                ':referral_other_text'      => $data['referral_other_text']       ?? null,
                ':notes'                    => $data['notes']                     ?? null,
                ':qbo_active_flag'          => isset($data['qbo_active_flag'])    ? (int)$data['qbo_active_flag'] : 0,
                ':qbo_customer_id'          => $data['qbo_customer_id']           ?? null,
                ':qbo_bill_with_parent'     => isset($data['qbo_bill_with_parent']) ? (int)$data['qbo_bill_with_parent'] : null,
                ':qbo_parent_ref'           => $data['qbo_parent_ref']            ?? null,
                ':qbo_sync_token'           => $data['qbo_sync_token']            ?? null,
                ':stripe_customer_id'       => $data['stripe_customer_id']        ?? null,
                ':created_by'               => $data['created_by']                ?? null,
            ]);

            // 2) Retrieve newly inserted customer_id
            $customerId = (int) $pdo->lastInsertId();

            // 3) Insert primary email if provided
            if (!empty($data['primary_email'])) {
                $stmtEmail = $pdo->prepare("
                    INSERT INTO customer_emails
                        (customer_id, email_address, email_type, is_primary, created_at)
                    VALUES
                        (:customer_id, :email_address, :email_type, 1, NOW())
                ");
                $stmtEmail->execute([
                    ':customer_id'   => $customerId,
                    ':email_address' => $data['primary_email'],
                    ':email_type'    => $data['email_type'] ?? 'Work',
                ]);
            }

            // 4) Insert primary phone if provided
            if (!empty($data['primary_phone'])) {
                $stmtPhone = $pdo->prepare("
                    INSERT INTO customer_phones
                        (customer_id, phone_number, phone_type, is_primary, created_at)
                    VALUES
                        (:customer_id, :phone_number, :phone_type, 1, NOW())
                ");
                $stmtPhone->execute([
                    ':customer_id'  => $customerId,
                    ':phone_number' => $data['primary_phone'],
                    ':phone_type'   => $data['phone_type'] ?? 'Mobile',
                ]);
            }

            // 5) Insert primary billing address if provided
            if (!empty($data['billing_address'])) {
                $stmtAddr = $pdo->prepare("
                    INSERT INTO customer_addresses
                        (customer_id, address_type,
                         address_line1, address_line2, city,
                         state_province, postal_code,
                         is_primary, created_at)
                    VALUES
                        (:customer_id, :address_type,
                         :address_line1, :address_line2, :city,
                         :state_province, :postal_code,
                         1, NOW())
                ");
                $stmtAddr->execute([
                    ':customer_id'    => $customerId,
                    ':address_type'   => 'Billing',
                    ':address_line1'  => $data['billing_address']  ?? '',
                    ':address_line2'  => $data['billing_address2'] ?? '',
                    ':city'           => $data['billing_city']     ?? '',
                    ':state_province' => $data['billing_state']    ?? '',
                    ':postal_code'    => $data['billing_zip']      ?? '',
                ]);
            }

            // Commit transaction
            $pdo->commit();
            return $customerId;

        } catch (Exception $ex) {
            // Rollback on error so we don't leave partial data
            $pdo->rollBack();
            throw $ex;
        }
    }
}
