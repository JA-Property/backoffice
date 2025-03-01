<?php
namespace App\Models;

use PDO;
use App\Database;

class Customer
{
    /**
     * Get a paginated list of customers with optional search and sorting.
     *
     * @param string|null $searchTerm   The text to search in name/email, etc.
     * @param string|null $sortColumn   e.g. "name", "propertyCount", "phone", "email".
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
    ): array 
    {
        $pdo = self::db();
    
        // Build base query
        $sql = "
            SELECT
                c.customer_id,
                c.display_name AS name,
                CONCAT(c.first_name, ' ', c.last_name) AS contact_name,
                e.primary_email AS email,
                ph.primary_phone AS phone,
                c.opening_balance,
                IFNULL(propCounts.property_count, 0) AS propertyCount,
    
                -- Suppose we also select c.status_id if you have that in DB
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
    
        // If searching by name/email
        if (!empty($searchTerm)) {
            $sql .= "
                AND (
                    c.display_name LIKE :search
                    OR c.first_name  LIKE :search
                    OR c.last_name   LIKE :search
                    OR e.primary_email LIKE :search
                )
            ";
            $params[':search'] = '%' . $searchTerm . '%';
        }
    
        // If status filter is set (e.g. "Active", "Overdue", etc.)
        if (!empty($statusFilter)) {
            $sql .= " AND c.status_id = :statusFilter";
            $params[':statusFilter'] = $statusFilter;
        }
    
        // Allowed sort columns
        $allowedSortCols = ['name','propertyCount','phone','email','opening_balance','status'];
        $orderByCol      = in_array($sortColumn, $allowedSortCols) ? $sortColumn : 'name';
        $orderByDir      = (strtoupper($sortOrder) === 'DESC') ? 'DESC' : 'ASC';
    
        $sql .= "
            ORDER BY $orderByCol $orderByDir
            LIMIT :offset, :limit
        ";
    
        $stmt = $pdo->prepare($sql);
    
        // Bind paging
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    
        // Bind search/status if present
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
    
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
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
                    c.display_name LIKE :search
                    OR c.first_name  LIKE :search
                    OR c.last_name   LIKE :search
                    OR e.primary_email LIKE :search
                )
            ";
            $params[':search'] = '%' . $searchTerm . '%';
        }
    
        // same idea for status
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
        return $row ? (int)$row['cnt'] : 0;
    }
    
    /**
     * (Optional) Sum of all customer opening balances (if you want a total).
     */
    public static function getTotalBalance(): float
    {
        $pdo = self::db();
        // If you no longer track a 'balance' column, you could rename it to 'opening_balance'
        $sql = "SELECT SUM(opening_balance) AS total_balance FROM customers";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row && $row['total_balance']
            ? (float)$row['total_balance']
            : 0.0;
    }

    /**
     * Example of a single-customer fetch by ID.
     */
    public static function getCustomerById(int $customerId): ?array
    {
        $pdo = self::db();
    
        // We select everything from customers (c.*) plus four extra columns:
        // 1) primary_email
        // 2) email_count
        // 3) primary_phone
        // 4) phone_count
        // In getCustomerById(), we'll add:

$sql = "
  SELECT
    c.*,

    -- Already existing email + phone sub-joins:
    e.primary_email,
    e.email_count,
    ph.primary_phone,
    ph.phone_count,

    -- New sub-join for primary billing address & billing_count:
    b.primary_billing,
    b.billing_count

  FROM customers c

  -- Sub-join for email (existing)
  LEFT JOIN (
      SELECT
          ce.customer_id,
          ce.email_address AS primary_email,
          (SELECT COUNT(*) FROM customer_emails WHERE customer_id = ce.customer_id) AS email_count
      FROM customer_emails ce
      WHERE ce.is_primary = 1
  ) AS e ON c.customer_id = e.customer_id

  -- Sub-join for phone (existing)
  LEFT JOIN (
      SELECT
          cp.customer_id,
          cp.phone_number AS primary_phone,
          (SELECT COUNT(*) FROM customer_phones WHERE customer_id = cp.customer_id) AS phone_count
      FROM customer_phones cp
      WHERE cp.is_primary = 1
  ) AS ph ON c.customer_id = ph.customer_id

  -- Sub-join for billing address (NEW)
  LEFT JOIN (
      SELECT
          ca.customer_id,
          -- Combine line1/line2/city/state/zip
          CONCAT(
              IFNULL(ca.address_line1, ''), 
              CASE WHEN ca.address_line2 != '' THEN CONCAT(', ', ca.address_line2) ELSE '' END,
              CASE WHEN ca.city != ''        THEN CONCAT(', ', ca.city)        ELSE '' END,
              CASE WHEN ca.state_province != '' THEN CONCAT(', ', ca.state_province) ELSE '' END,
              CASE WHEN ca.postal_code != '' THEN CONCAT(' ', ca.postal_code)  ELSE '' END
          ) AS primary_billing,

          (SELECT COUNT(*) 
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
     * Retrieve all addresses for a given customer (using your new 'customer_addresses' table).
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
     * Retrieve all properties for a given customer (if you still have a 'properties' table).
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
     * Retrieve all notes for a given customer (if you still have a 'customer_notes' table).
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
     * Alias for getCustomerById
     */
    public static function find(int $customerId): ?array
    {
        return self::getCustomerById($customerId);
    }

    /**
     * If you still need to batch update something (e.g., default discount).
     * Otherwise remove or adapt for your new schema.
     */
    public static function batchUpdateDiscount(array $customerIds, bool $enableDiscount): int
    {
        if (empty($customerIds)) {
            return 0;
        }
        $pdo = self::db();
        $placeholders = implode(',', array_fill(0, count($customerIds), '?'));

        $sql = "UPDATE customers
                SET default_discount_enabled = :enableDiscount
                WHERE customer_id IN ($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':enableDiscount', $enableDiscount, PDO::PARAM_BOOL);

        // Bind each ID in the array
        $i = 1;
        foreach ($customerIds as $id) {
            $stmt->bindValue($i, $id, PDO::PARAM_INT);
            $i++;
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Stub function for sending emails. Implementation up to you.
     */
    public static function batchSendEmail(array $customerIds): void
    {
        // Example:
        // foreach ($customerIds as $cid) { sendEmailToCustomer($cid); }
    }

    /**
     * Use your environment-based DB connection via App\Database
     */
    protected static function db(): PDO
    {
        return Database::connect();
    }


/**
 * Create a new customer along with their primary email, phone, and billing address.
 * Inserts all possible columns from the "customers" table.
 *
 * @param array $data The form data (e.g., $_POST).
 * @return int The new customer_id.
 * @throws \Exception If something fails during the insert.
 */
public static function createCustomer(array $data): int
{
    var_dump($_POST);
    
    $firstName = null;
    $lastName  = null;
    $fullyQualifiedName = null;
    $displayName = null;
    
    // If they used Residential fields
    if (!empty($data['res_first_name']) || !empty($data['res_last_name'])) {
        $firstName = $data['res_first_name'] ?? '';
        $lastName  = $data['res_last_name']  ?? '';
        // Combine first and last, convert to lowercase then uppercase the first letter of each word
        $displayName = ucwords(strtolower(trim($firstName . ' ' . $lastName)));
        $fullyQualifiedName = null;
    }
    // Else if they used Commercial fields
    elseif (!empty($data['com_first_name']) || !empty($data['com_last_name'])) {
        $firstName = $data['com_first_name'] ?? '';
        $lastName  = $data['com_last_name']  ?? '';
        // Use the provided display name if available; otherwise, you could also combine first and last
        $fullyQualifiedName = ucwords(strtolower($data['company_name'] ?? ''));
        $displayName = $fullyQualifiedName;

    }
    // Else if they used Other fields
    elseif (!empty($data['oth_first_name']) || !empty($data['oth_last_name'])) {
        $firstName = $data['oth_first_name'] ?? '';
        $lastName  = $data['oth_last_name']  ?? '';
        $fullyQualifiedName = ucwords(strtolower($data['organization_name'] ?? ''));
        $displayName = $fullyQualifiedName;

    }
    
    
    $pdo = self::db();
    $pdo->beginTransaction();

    try {
        // Insert into `customers` with ALL columns from the schema:
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



        // Prepare insert
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':customer_type'            => $data['customer_type']             ?? 'Residential',
            ':display_name'             => $displayName ?? null,
            ':company_name'             => $fullyQualifiedName              ?? null,
            ':title'                    => $data['title']                     ?? null,
            ':first_name'               => $firstName                ?? null,
            ':middle_name'              => $data['middle_name']               ?? null,
            ':last_name'                => $lastName                            ?? null,
            ':suffix'                   => $data['suffix']                    ?? null,
            ':print_name_on_check'      => $fullyQualifiedName      ?? null,
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

        // Get new customer_id
        $customerId = (int)$pdo->lastInsertId();

        // 2) Insert primary email, if provided
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
                ':email_type'    => $data['email_type'] ?? 'Work', // or 'Personal', etc.
            ]);
        }

        // 3) Insert primary phone, if provided
        if (!empty($data['primary_phone'])) {
            $stmtPhone = $pdo->prepare("
                INSERT INTO customer_phones
                    (customer_id, phone_number, phone_type, is_primary, created_at)
                VALUES
                    (:customer_id, :phone_number, :phone_type, 1, NOW())
            ");
            $stmtPhone->execute([
                ':customer_id' => $customerId,
                ':phone_number'=> $data['primary_phone'],
                ':phone_type'  => $data['phone_type'] ?? 'Mobile',
            ]);
        }

        // 4) Insert billing address, if provided
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
                ':address_type'   => 'Billing',  // If your form has other address types, adapt accordingly
                ':address_line1'  => $data['billing_address']  ?? '',
                ':address_line2'  => $data['billing_address2'] ?? '',
                ':city'           => $data['billing_city']     ?? '',
                ':state_province' => $data['billing_state']    ?? '',
                ':postal_code'    => $data['billing_zip']      ?? '',
            ]);
        }

        // 5) Commit transaction
        $pdo->commit();
        return $customerId;

    } catch (\Exception $ex) {
        // If any insert fails, rollback so no partial data remains
        $pdo->rollBack();
        throw $ex;
    }
}


}
