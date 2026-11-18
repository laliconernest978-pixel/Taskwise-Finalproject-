-- Run this in phpMyAdmin with taskwise_db selected.
-- The existing five tables are left unchanged.
CREATE TABLE IF NOT EXISTS `taskwise_app_data` (
  `storage_key` VARCHAR(80) NOT NULL,
  `storage_value` LONGTEXT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`storage_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
