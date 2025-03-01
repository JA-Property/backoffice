CREATE TABLE IF NOT EXISTS customers (
  -- (I) Primary Key
  customer_id BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  
  -- (II) Customer Type & Name Fields
  customer_type ENUM('Residential','Commercial','Other') NOT NULL DEFAULT 'Residential',
  display_name VARCHAR(100) NOT NULL,
  company_name VARCHAR(100),
  title VARCHAR(16),
  first_name VARCHAR(100) NOT NULL,
  middle_name VARCHAR(100),
  last_name VARCHAR(100) NOT NULL,
  suffix VARCHAR(16),
  
  -- (III) Formatting & Identification
  print_name_on_check VARCHAR(100),
  org_type VARCHAR(100),                -- Possibly for internal classification
  
  -- (IV) Communication Preferences
  comm_email BOOLEAN NOT NULL DEFAULT FALSE,
  comm_phone BOOLEAN NOT NULL DEFAULT FALSE,
  comm_sms   BOOLEAN NOT NULL DEFAULT FALSE,
  preferred_delivery_method ENUM('USPS','Email','SMS') NOT NULL DEFAULT 'USPS',
  
  -- (V) Billing & Discount Fields
  billing_format ENUM('Invoice','Statement') NOT NULL DEFAULT 'Invoice',
  invoice_prefix INT,
  opening_balance DECIMAL(15,2) DEFAULT NULL,
  opening_balance_date DATE,
  discount_type ENUM('Flat','Percentage'),
  discount_amount DECIMAL(15,2) DEFAULT NULL,
  default_discount_enabled BOOLEAN NOT NULL DEFAULT FALSE,
  terms VARCHAR(50),
  
  -- (VI) Tax Exemption
  tax_exempt ENUM('exempt','none','reverse') NOT NULL DEFAULT 'none',
  tax_exempt_id VARCHAR(50),
  tax_exempt_reason_code TINYINT,
  
  -- (VII) Misc. Info & Notes
  resale_number VARCHAR(16),
  referral_source VARCHAR(100),
  referral_other_text VARCHAR(255),
  notes TEXT,
  
  -- (VIII) QuickBooks (QBO) Integration
  qbo_active_flag BOOLEAN NOT NULL DEFAULT TRUE,
  qbo_customer_id VARCHAR(50),
  qbo_bill_with_parent BOOLEAN,
  qbo_parent_ref VARCHAR(50),
  qbo_sync_token VARCHAR(50),
  
  -- (IX) Stripe Integration
  stripe_customer_id VARCHAR(50),
  
  -- (X) Audit Fields
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  created_by INT,                -- Can reference a users/employees/staff table if desired
  
  -- (XI) Indexes & Constraints
  PRIMARY KEY (customer_id),
  UNIQUE KEY uq_qbo_customer_id (qbo_customer_id),
  UNIQUE KEY uq_stripe_customer_id (stripe_customer_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS customer_emails (
  customer_email_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,
  
  email_type ENUM('Personal','Work','Other') NOT NULL DEFAULT 'Personal',
  email_address VARCHAR(255) NOT NULL,
  
  -- Verification / Status
  is_primary BOOLEAN NOT NULL DEFAULT FALSE,
  is_verified BOOLEAN NOT NULL DEFAULT FALSE,
  verification_token VARCHAR(100),
  
  -- Audit
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (customer_email_id),
  KEY idx_email_customer (customer_id),
  
  CONSTRAINT fk_customer_emails_customer
    FOREIGN KEY (customer_id)
    REFERENCES customers(customer_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS customer_phones (
  customer_phone_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,
  
  phone_type ENUM('Mobile','Work','Home','Fax','Other') NOT NULL,
  country_code VARCHAR(5) DEFAULT '+1',
  phone_number VARCHAR(20) NOT NULL,
  extension VARCHAR(10),
  
  -- Status / Flags
  is_primary BOOLEAN NOT NULL DEFAULT FALSE,
  is_verified BOOLEAN NOT NULL DEFAULT FALSE,
  verification_token VARCHAR(100),
  
  -- Audit
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (customer_phone_id),
  KEY idx_phones_customer (customer_id),
  
  CONSTRAINT fk_customer_phones_customer
    FOREIGN KEY (customer_id)
    REFERENCES customers(customer_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS customer_addresses (
  customer_address_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,

  address_type ENUM('Billing','Shipping','Mailing','Other') NOT NULL,
  
  -- Address Lines
  address_line1 VARCHAR(255) NOT NULL,
  address_line2 VARCHAR(255),
  city VARCHAR(100),
  state_province VARCHAR(100),
  postal_code VARCHAR(20),
  
  -- Status / Flags
  is_primary BOOLEAN NOT NULL DEFAULT FALSE,
  is_verified BOOLEAN NOT NULL DEFAULT FALSE,
  
  -- Audit
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (customer_address_id),
  KEY idx_addresses_customer (customer_id),
  
  CONSTRAINT fk_customer_addresses_customer
    FOREIGN KEY (customer_id)
    REFERENCES customers(customer_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;
