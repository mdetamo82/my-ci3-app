#
# TABLE STRUCTURE FOR: backup_logs
#

DROP TABLE IF EXISTS `backup_logs`;

CREATE TABLE `backup_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `type` enum('Manual','Cron') DEFAULT 'Manual',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (1, 'backup-20250626_235608.zip', '6416', 'Manual', 1, '2025-06-26 23:56:08');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (2, 'backup-20250626_235940.zip', '6474', 'Manual', 1, '2025-06-26 23:59:40');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (3, 'backup-20250626_235952.zip', '6506', 'Manual', 1, '2025-06-26 23:59:52');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (4, 'backup-20250627_000252.zip', '6533', 'Manual', 1, '2025-06-27 00:02:52');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (5, 'backup-20250627_000417.zip', '6559', 'Manual', 1, '2025-06-27 00:04:17');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (6, 'backup-20250627_003930.zip', '6579', 'Manual', 1, '2025-06-27 00:39:30');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (7, 'backup-20250627_003934.zip', '6601', 'Manual', 1, '2025-06-27 00:39:34');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (8, 'backup-20250627_003938.zip', '6612', 'Manual', 1, '2025-06-27 00:39:38');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (9, 'backup-20250627_010709.zip', '6600', 'Manual', 1, '2025-06-27 01:07:09');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (10, 'backup-20250627_010716.zip', '6634', 'Manual', 1, '2025-06-27 01:07:16');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (11, 'backup-20250627_011433.zip', '6641', 'Manual', 1, '2025-06-27 01:14:33');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (12, 'backup-20250627_011447.zip', '6679', 'Manual', 1, '2025-06-27 01:14:47');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (13, 'backup-20250627_011448.zip', '6703', 'Manual', 1, '2025-06-27 01:14:48');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (14, 'backup-20250627_011449.zip', '6718', 'Manual', 1, '2025-06-27 01:14:49');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (15, 'backup-20250627_011451.zip', '6742', 'Manual', 1, '2025-06-27 01:14:51');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (16, 'backup-20250627_011451.zip', '6752', 'Manual', 1, '2025-06-27 01:14:52');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (17, 'backup-20250627_011452.zip', '6768', 'Manual', 1, '2025-06-27 01:14:52');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (18, 'backup-20250627_011452.zip', '6783', 'Manual', 1, '2025-06-27 01:14:52');
INSERT INTO `backup_logs` (`id`, `file_name`, `file_size`, `type`, `created_by`, `created_at`) VALUES (19, 'backup-20250627_102638.zip', '6800', 'Manual', 1, '2025-06-27 10:26:38');


#
# TABLE STRUCTURE FOR: backup_settings
#

DROP TABLE IF EXISTS `backup_settings`;

CREATE TABLE `backup_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `auto_backup` tinyint(1) DEFAULT 0,
  `backup_frequency` varchar(20) DEFAULT 'daily',
  `retention_period` int(11) DEFAULT 30,
  `backup_time` time DEFAULT '02:00:00',
  `notify_email` varchar(255) DEFAULT NULL,
  `compress_backups` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `backup_settings` (`id`, `auto_backup`, `backup_frequency`, `retention_period`, `backup_time`, `notify_email`, `compress_backups`, `created_at`, `updated_at`) VALUES (1, 0, 'weekly', 30, '02:00:00', '', 1, '2025-06-26 23:59:49', '2025-06-26 23:59:49');


#
# TABLE STRUCTURE FOR: group_permissions
#

DROP TABLE IF EXISTS `group_permissions`;

CREATE TABLE `group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_permission` (`group_id`,`permission_id`),
  KEY `group_id` (`group_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `fk_gp_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_gp_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=807 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (521, 3, 36, '2025-06-19 11:54:09');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (764, 1, 36, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (765, 1, 37, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (766, 1, 38, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (767, 1, 39, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (768, 1, 40, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (769, 1, 45, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (770, 1, 49, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (771, 1, 53, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (772, 1, 54, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (773, 1, 55, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (774, 1, 56, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (775, 1, 57, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (776, 1, 58, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (777, 1, 59, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (778, 1, 60, '2025-06-30 00:20:35');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (780, 2, 36, '2025-06-30 00:21:22');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (781, 2, 40, '2025-06-30 00:21:22');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (782, 2, 45, '2025-06-30 00:21:22');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (802, 4, 36, '2025-07-01 15:35:05');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (803, 4, 38, '2025-07-01 15:35:05');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (804, 4, 49, '2025-07-01 15:35:05');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (805, 4, 50, '2025-07-01 15:35:05');
INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `created_at`) VALUES (806, 4, 51, '2025-07-01 15:35:05');


#
# TABLE STRUCTURE FOR: groups
#

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES (1, 'admin', 'Administrator');
INSERT INTO `groups` (`id`, `name`, `description`) VALUES (2, 'members', 'General User');
INSERT INTO `groups` (`id`, `name`, `description`) VALUES (3, 'sales', 'sales');
INSERT INTO `groups` (`id`, `name`, `description`) VALUES (4, 'staff', 'staff');
INSERT INTO `groups` (`id`, `name`, `description`) VALUES (5, 'superadmin', 'Super Administrator');


#
# TABLE STRUCTURE FOR: hawalas
#

DROP TABLE IF EXISTS `hawalas`;

CREATE TABLE `hawalas` (
  `hawala_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mark` varchar(255) NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`hawala_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `hawalas` (`hawala_id`, `name`, `mark`, `balance`, `currency`, `mobile`, `address`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (42, 'Mustefa Detamo', 'HSM', '0.00', 'SAR', '0912121212', 'wwww', 1, 1, '2025-06-07 20:07:08', 1, '2025-06-29 21:30:33');
INSERT INTO `hawalas` (`hawala_id`, `name`, `mark`, `balance`, `currency`, `mobile`, `address`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (43, 'MUSTEFA DETAMO', 'MD102', '0.00', 'USD', '0913131313', 'Dukem', 1, 1, '2025-06-18 23:19:08', 1, '2025-06-23 00:02:51');
INSERT INTO `hawalas` (`hawala_id`, `name`, `mark`, `balance`, `currency`, `mobile`, `address`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (44, 'AGOT', 'AGB', '-13000.00', 'CNY', '0917171717', 'SAR', 1, 1, '2025-06-23 13:43:23', 1, '2025-06-26 13:23:47');
INSERT INTO `hawalas` (`hawala_id`, `name`, `mark`, `balance`, `currency`, `mobile`, `address`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (45, 'hussen', 'MDD', '0.00', 'AED', '0912121212', 'addis', 1, 1, '2025-06-26 13:24:38', NULL, NULL);
INSERT INTO `hawalas` (`hawala_id`, `name`, `mark`, `balance`, `currency`, `mobile`, `address`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (46, 'MUHAMMED', 'MUHA', '252353.68', 'AED', '0912121212', 'ADDIS', 1, 1, '2025-06-29 21:31:55', NULL, NULL);


#
# TABLE STRUCTURE FOR: loans
#

DROP TABLE IF EXISTS `loans`;

CREATE TABLE `loans` (
  `loan_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'User who created this record',
  `updated_by` int(10) unsigned DEFAULT NULL COMMENT 'User who last updated this record',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`loan_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `loans` (`loan_id`, `name`, `mobile`, `address`, `balance`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (3, 'Sherefa mudesire', '0913131313', 'Dukem', '0.00', 1, 1, 1, '2025-05-14 20:12:40', '2025-06-29 21:30:01');
INSERT INTO `loans` (`loan_id`, `name`, `mobile`, `address`, `balance`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (5, 'MUHAMMED SURUR', '0912121212', 'Dukem', '225221.66', 1, 1, NULL, '2025-06-19 10:17:32', '2025-06-30 21:47:45');
INSERT INTO `loans` (`loan_id`, `name`, `mobile`, `address`, `balance`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (6, 'ismail', '0912121212', 'addis abeba', '9324.00', 1, 1, NULL, '2025-07-01 14:17:14', '2025-07-01 15:20:02');


#
# TABLE STRUCTURE FOR: login_attempts
#

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#
# TABLE STRUCTURE FOR: permissions
#

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (36, 'staff_view_staff', 'View Staff', 'staff', 'view_staff', '2025-06-19 11:52:53', '2025-06-19 11:52:53');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (37, 'staff_add_staff', 'Add Staff', 'staff', 'add_staff', '2025-06-19 11:52:53', '2025-06-19 11:52:53');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (38, 'staff_edit_staff', 'Edit Staff', 'staff', 'edit_staff', '2025-06-19 11:52:53', '2025-06-19 11:52:53');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (39, 'staff_delete_staff', 'Delete Staff', 'staff', 'delete_staff', '2025-06-19 11:52:54', '2025-06-19 11:52:54');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (40, 'hawala_view_hawala', 'View Hawala', 'hawala', 'view_hawala', '2025-06-19 11:52:54', '2025-06-19 11:52:54');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (41, 'hawala_add_hawala', 'Add Hawala', 'hawala', 'add_hawala', '2025-06-19 11:52:54', '2025-06-19 11:52:54');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (42, 'hawala_edit_hawala', 'Edit Hawala', 'hawala', 'edit_hawala', '2025-06-19 11:52:55', '2025-06-19 11:52:55');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (43, 'hawala_delete_hawala', 'Delete Hawala', 'hawala', 'delete_hawala', '2025-06-19 11:52:55', '2025-06-19 11:52:55');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (45, 'loan_view_loan', 'View Loan', 'loan', 'view_loan', '2025-06-19 14:15:59', '2025-06-19 14:15:59');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (46, 'loan_add_loan', 'Add Loan', 'loan', 'add_loan', '2025-06-19 14:16:00', '2025-06-19 14:16:00');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (47, 'loan_edit_loan', 'Edit Loan', 'loan', 'edit_loan', '2025-06-19 14:16:00', '2025-06-19 14:16:00');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (48, 'loan_delete_loan', 'Delete Loan', 'loan', 'delete_loan', '2025-06-19 14:16:01', '2025-06-19 14:16:01');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (49, 'Staff_transaction_view_staff_transaction', 'Staff Transaction', 'Staff_transaction', 'view_staff_transaction', '2025-06-19 22:10:30', '2025-06-19 22:10:30');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (50, 'Staff_transaction_add_staff_transaction', 'Add Staff Transaction', 'Staff_transaction', 'add_staff_transaction', '2025-06-19 22:10:31', '2025-06-19 22:10:31');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (51, 'Staff_transaction_edit_staff_transaction', 'Edit Staff Transaction', 'Staff_transaction', 'edit_staff_transaction', '2025-06-19 22:10:31', '2025-06-19 22:10:31');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (52, 'Staff_transaction_delete_staff_transaction', 'Delete Staff Transaction', 'Staff_transaction', 'delete_staff_transaction', '2025-06-19 22:10:32', '2025-06-19 22:10:32');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (53, 'Hawala_transaction_view_hawala_transaction', 'Hawala Transaction', 'Hawala_transaction', 'view_hawala_transaction', '2025-06-19 22:10:32', '2025-06-19 22:10:32');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (54, 'Hawala_transaction_add_hawala_transaction', 'Add Hawala Transaction', 'Hawala_transaction', 'add_hawala_transaction', '2025-06-19 22:10:32', '2025-06-19 22:10:32');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (55, 'Hawala_transaction_edit_hawala_transaction', 'Edit Hawala Transaction', 'Hawala_transaction', 'edit_hawala_transaction', '2025-06-19 22:10:32', '2025-06-19 22:10:32');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (56, 'Hawala_transaction_delete_hawala_transaction', 'Delete Hawala Transaction', 'Hawala_transaction', 'delete_hawala_transaction', '2025-06-19 22:10:32', '2025-06-19 22:10:32');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (57, 'Loan_transaction_view_loan_transaction', 'Loan Transaction', 'Loan_transaction', 'view_loan_transaction', '2025-06-19 22:10:33', '2025-06-19 22:10:33');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (58, 'Loan_transaction_add_loan_transaction', 'Add Loan Transaction', 'Loan_transaction', 'add_loan_transaction', '2025-06-19 22:10:33', '2025-06-19 22:10:33');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (59, 'Loan_transaction_edit_loan_transaction', 'Edit Loan Transaction', 'Loan_transaction', 'edit_loan_transaction', '2025-06-19 22:10:33', '2025-06-19 22:10:33');
INSERT INTO `permissions` (`id`, `name`, `description`, `controller`, `method`, `created_at`, `updated_at`) VALUES (60, 'Loan_transaction_delete_loan_transaction', 'Delete Loan Transaction', 'Loan_transaction', 'delete_loan_transaction', '2025-06-19 22:10:34', '2025-06-19 22:10:34');


#
# TABLE STRUCTURE FOR: permissions_generated
#

DROP TABLE IF EXISTS `permissions_generated`;

CREATE TABLE `permissions_generated` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `permissions_generated` (`id`, `timestamp`) VALUES (1, 1743974519);


#
# TABLE STRUCTURE FOR: staff
#

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `staff_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'User who created this record',
  `updated_by` int(10) unsigned DEFAULT NULL COMMENT 'User who last updated this record',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`staff_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_department` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `staff` (`staff_id`, `name`, `mobile`, `address`, `department`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (86, 'SHEMSEDIN WABELLA', '0913131313', 'addis abeba', 'Accounting', 1, 1, 1, '2025-05-04 11:34:13', '2025-06-27 09:41:46');
INSERT INTO `staff` (`staff_id`, `name`, `mobile`, `address`, `department`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (91, 'ABDURHMAN JEMAL', '0912121212', 'addis abeba', 'Accounting', 1, 1, 1, '2025-05-07 13:09:29', '2025-07-01 13:16:07');
INSERT INTO `staff` (`staff_id`, `name`, `mobile`, `address`, `department`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (94, 'SEBAHADIN NASIR', '0913188662', 'addis abeba', 'ACCOUNTING', 1, 1, 1, '2025-06-18 22:55:58', '2025-06-27 09:41:32');
INSERT INTO `staff` (`staff_id`, `name`, `mobile`, `address`, `department`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES (95, 'TEMIMA HUSSEN', '0912121212', 'addis abeba', 'Accounting', 1, 1, 1, '2025-06-23 21:00:31', '2025-07-01 13:22:19');


#
# TABLE STRUCTURE FOR: staff_bank
#

DROP TABLE IF EXISTS `staff_bank`;

CREATE TABLE `staff_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(100) NOT NULL,
  `balance` decimal(16,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'User who created this record',
  `updated_by` int(10) unsigned DEFAULT NULL COMMENT 'User who last updated this record',
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (6, 91, 'ABAY', '10000000000', '891.00', '2025-05-08 20:26:57', '2025-06-27 23:08:47', 1, 1);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (10, 91, 'AWASH', '1002011211', '162352.00', '2025-05-09 08:21:30', '2025-07-01 15:20:02', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (13, 91, 'COOP', '1000242342432', '1977.00', '2025-05-09 22:51:56', '2025-06-30 21:47:46', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (14, 91, 'DASHEN', '10000000', '20010.00', '2025-05-09 22:55:13', '2025-06-23 15:04:37', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (31, 91, 'HIBRET', '0123121312', '60810.00', '2025-05-10 06:10:38', '2025-06-27 23:30:26', 1, 1);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (34, 86, 'HIBRET', '10000000000', '1446199.20', '2025-06-10 20:36:33', '2025-06-28 01:45:55', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (35, 94, 'BOA', '1000242342432', '20000.00', '2025-06-18 22:59:31', '2025-06-26 14:26:44', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (36, 94, 'COOP', '0123121312', '120000.00', '2025-06-18 22:59:48', '2025-06-24 23:57:31', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (37, 95, 'CBE', '1002011211', '17693000.00', '2025-06-23 21:02:09', '2025-06-29 23:31:59', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (38, 95, 'BOA', '10000000000', '-50.00', '2025-06-23 21:02:41', '2025-06-28 01:56:00', 1, NULL);
INSERT INTO `staff_bank` (`id`, `staff_id`, `name`, `number`, `balance`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (39, 91, 'CBE', '1002011211', '130000.00', '2025-07-01 13:19:05', '2025-07-01 15:25:53', 1, NULL);


#
# TABLE STRUCTURE FOR: tbl_currencies
#

DROP TABLE IF EXISTS `tbl_currencies`;

CREATE TABLE `tbl_currencies` (
  `code` varchar(5) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `symbol` varchar(5) DEFAULT NULL,
  `xrate` decimal(12,5) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tbl_currencies` (`code`, `name`, `symbol`, `xrate`) VALUES ('AED', 'Dirham', 'AED', NULL);
INSERT INTO `tbl_currencies` (`code`, `name`, `symbol`, `xrate`) VALUES ('CNY', 'Renminbi', '¥', NULL);
INSERT INTO `tbl_currencies` (`code`, `name`, `symbol`, `xrate`) VALUES ('SAR', 'Saudi Riyal', 'SAR', NULL);
INSERT INTO `tbl_currencies` (`code`, `name`, `symbol`, `xrate`) VALUES ('USD', 'US Dollar', '$', NULL);


#
# TABLE STRUCTURE FOR: transactions
#

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notes` varchar(255) DEFAULT NULL,
  `birr` decimal(18,2) DEFAULT 0.00 CHECK (`birr` >= 0.00),
  `amount` decimal(18,2) DEFAULT 0.00 CHECK (`amount` >= 0.00),
  `rate` decimal(16,2) DEFAULT 0.00 CHECK (`rate` >= 0),
  `currency` varchar(10) DEFAULT NULL,
  `debit` decimal(18,2) NOT NULL DEFAULT 0.00 CHECK (`debit` >= 0.00),
  `credit` decimal(18,2) NOT NULL DEFAULT 0.00 CHECK (`credit` >= 0.00),
  `transaction_type` enum('staff','hawala','loan','hawala_staff') NOT NULL,
  `type` enum('Income','Expense') NOT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `hawala_id` int(11) DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_transaction_type` (`transaction_type`),
  KEY `idx_date` (`date`),
  KEY `idx_staff_id` (`staff_id`),
  KEY `idx_hawala_id` (`hawala_id`),
  KEY `idx_loan_id` (`loan_id`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (99, '', '0.00', '23.00', '0.00', NULL, '0.00', '0.00', 'hawala', 'Income', NULL, NULL, 42, NULL, NULL, NULL, '2025-06-17', 1, 1, 1, '2025-06-17 23:30:03', '2025-06-18 22:52:14');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (105, '', '0.00', '2.00', '0.00', NULL, '0.00', '0.00', 'hawala', 'Income', NULL, NULL, 42, NULL, NULL, NULL, '2025-06-18', 1, 1, 1, '2025-06-18 20:38:34', '2025-06-18 22:48:16');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (108, 's', '23.00', '23.00', '0.00', 'SAR', '0.00', '23.00', 'hawala_staff', 'Income', NULL, 86, 42, NULL, 34, NULL, '2025-06-18', 1, 1, 1, '2025-06-18 21:46:32', '2025-06-23 22:05:51');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (109, 'note', '1000000.00', '6451.61', '155.00', 'SAR', '0.00', '0.00', 'hawala_staff', 'Income', NULL, 86, 42, NULL, 34, NULL, '2025-06-18', 1, NULL, 1, '2025-06-18 21:51:39', '2025-06-18 22:51:39');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (119, 'weee', '70.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 86, NULL, NULL, 34, NULL, '2025-06-19', 1, 1, 1, '2025-06-19 10:35:15', '2025-06-21 21:10:51');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (120, 'wwww', '0.00', '2020.00', '0.00', NULL, '0.00', '2020.00', 'hawala', 'Income', NULL, NULL, 43, NULL, NULL, NULL, '2025-06-19', 1, 1, 1, '2025-06-19 23:22:05', '2025-06-20 18:25:52');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (121, 'wwewe', '0.00', '3220.00', '0.00', NULL, '0.00', '3220.00', 'hawala', 'Income', NULL, NULL, 43, NULL, NULL, NULL, '2025-06-20', 1, 1, 1, '2025-06-20 14:48:09', '2025-06-21 21:12:32');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (122, 'wwww', '20.00', '20.00', '1.00', 'SAR', '0.00', '20.00', 'hawala_staff', 'Income', NULL, 91, 42, NULL, 10, NULL, '2025-06-24', 1, 1, 1, '2025-06-21 05:07:55', '2025-06-23 23:12:41');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (123, '3423', '10.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 14, NULL, '2025-06-22', 1, 1, 1, '2025-06-22 22:38:12', '2025-06-23 14:04:37');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (124, '', '0.00', '2.00', '0.00', NULL, '0.00', '2.00', 'hawala', 'Income', NULL, NULL, 42, NULL, NULL, NULL, '2025-06-23', 1, NULL, 1, '2025-06-23 00:04:07', '2025-06-23 01:04:07');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (125, '', '20000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 14, NULL, '2025-06-23', 1, NULL, 1, '2025-06-23 12:39:17', '2025-06-23 13:39:17');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (127, '', '20000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 10, NULL, '2025-06-23', 1, NULL, 1, '2025-06-23 12:45:17', '2025-06-23 13:45:17');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (128, '', '10000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 10, NULL, '2025-06-23', 1, NULL, 1, '2025-06-23 13:32:15', '2025-06-23 14:32:15');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (130, 'car income ', '100000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 95, NULL, NULL, 37, NULL, '2025-06-23', 1, NULL, 1, '2025-06-23 21:04:32', '2025-06-23 22:04:32');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (131, 'ren', '50.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Expense', NULL, 95, NULL, NULL, 38, NULL, '2025-06-23', 1, 1, 1, '2025-06-23 21:10:18', '2025-06-23 23:10:01');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (132, '', '20000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 86, NULL, NULL, 34, NULL, '2025-06-24', 1, NULL, 1, '2025-06-24 22:25:34', '2025-06-24 23:25:34');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (133, '', '20000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Expense', NULL, 91, NULL, NULL, 10, NULL, '2025-06-24', 1, NULL, 1, '2025-06-24 22:25:52', '2025-06-24 23:25:52');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (134, 'e', '0.00', '10000.00', '0.00', NULL, '0.00', '10000.00', 'hawala', 'Income', NULL, NULL, 42, NULL, NULL, NULL, '2025-06-24', 1, NULL, 1, '2025-06-24 22:30:01', '2025-06-24 23:30:01');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (135, 'we', '120000.00', '566.04', '212.00', 'SAR', '0.00', '566.04', 'hawala_staff', 'Income', NULL, 94, 42, NULL, 36, NULL, '2025-06-24', 1, NULL, 1, '2025-06-24 22:57:31', '2025-06-24 23:57:31');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (136, 'WEe', '2000.00', '16.13', '124.00', 'USD', '0.00', '16.13', 'hawala_staff', 'Income', NULL, 91, 43, NULL, 13, NULL, '2025-06-24', 1, 1, 1, '2025-06-24 23:13:08', '2025-07-01 13:17:49');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (140, '', '200000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'loan', 'Income', NULL, 86, NULL, 5, 34, NULL, '2025-06-24', 1, NULL, 1, '2025-06-24 23:25:55', '2025-06-25 00:25:55');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (141, '', '12000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 86, NULL, NULL, 34, NULL, '2025-06-25', 1, NULL, 1, '2025-06-25 22:39:22', '2025-06-25 23:39:22');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (143, 'note test ', '1000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 6, NULL, '2025-06-25', 1, 1, 1, '2025-06-25 23:21:08', '2025-06-27 22:08:47');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (144, '', '12000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 94, NULL, NULL, 35, NULL, '2025-06-25', 1, NULL, 1, '2025-06-25 23:28:19', '2025-06-26 00:28:19');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (145, '', '2000.00', '51.28', '39.00', 'USD', '0.00', '51.28', 'hawala_staff', 'Income', NULL, 94, 43, NULL, 35, NULL, '2025-06-26', 1, NULL, 1, '2025-06-26 12:51:14', '2025-06-26 13:51:14');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (146, '', '2000.00', '86.96', '23.00', 'USD', '0.00', '86.96', 'hawala_staff', 'Income', NULL, 94, 43, NULL, 35, NULL, '2025-06-26', 1, NULL, 1, '2025-06-26 12:51:59', '2025-06-26 13:51:59');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (147, 'kkkk', '12000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 94, NULL, NULL, 35, NULL, '2025-06-26', 1, NULL, 1, '2025-06-26 12:52:44', '2025-06-26 13:52:44');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (148, 'loan out', '8000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'loan', 'Expense', NULL, 94, NULL, 5, 35, NULL, '2025-06-26', 1, NULL, 1, '2025-06-26 13:26:44', '2025-06-26 14:26:44');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (162, '', '20000.00', '0.00', '0.00', NULL, '0.00', '20000.00', 'loan', 'Income', NULL, 95, NULL, 5, 37, NULL, '2025-06-28', 1, NULL, 1, '2025-06-28 00:32:29', '2025-06-28 01:32:29');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (163, '', '20000.00', '0.00', '0.00', NULL, '20000.00', '0.00', 'loan', 'Expense', NULL, 95, NULL, 5, 37, NULL, '2025-06-28', 1, NULL, 1, '2025-06-28 00:34:39', '2025-06-28 01:34:39');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (164, '', '100000.00', '0.00', '0.00', NULL, '0.00', '100000.00', 'loan', 'Income', NULL, 86, NULL, 5, 34, NULL, '2025-06-28', 1, NULL, 1, '2025-06-28 00:45:55', '2025-06-28 01:45:55');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (165, '', '100000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'loan', 'Expense', NULL, 95, NULL, 5, 37, NULL, '2025-06-28', 1, NULL, 1, '2025-06-28 00:55:26', '2025-06-28 01:55:26');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (166, 'FORWARD', '0.00', '337384.00', '0.00', NULL, '0.00', '337384.00', 'hawala', 'Income', NULL, NULL, 46, NULL, NULL, NULL, '2025-02-05', 1, NULL, 1, '2025-06-29 21:35:53', '2025-06-29 22:35:53');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (167, '', '3159000.00', '79672.13', '39.65', 'AED', '0.00', '79672.13', 'hawala_staff', 'Income', NULL, 95, 46, NULL, 37, NULL, '2025-06-29', 1, NULL, 1, '2025-06-29 21:44:41', '2025-06-29 22:44:41');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (168, 'OFFICE ', '0.00', '321000.00', '0.00', NULL, '321000.00', '0.00', 'hawala', 'Expense', NULL, NULL, 46, NULL, NULL, NULL, '2025-02-07', 1, 1, 1, '2025-06-29 22:22:33', '2025-06-29 22:28:53');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (169, '', '4399000.00', '110945.78', '39.65', 'AED', '0.00', '110945.78', 'hawala_staff', 'Income', NULL, 95, 46, NULL, 37, NULL, '2025-02-08', 1, 1, 1, '2025-06-29 22:25:54', '2025-06-29 22:31:59');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (170, '', '9055000.00', '228085.64', '39.70', 'AED', '0.00', '228085.64', 'hawala_staff', 'Income', NULL, 95, 46, NULL, 37, NULL, '2025-02-08', 1, NULL, 1, '2025-06-29 22:27:22', '2025-06-29 23:27:22');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (171, '', '1000000.00', '25220.68', '39.65', 'AED', '0.00', '25220.68', 'hawala_staff', 'Income', NULL, 95, 46, NULL, 37, NULL, '2025-02-09', 1, NULL, 1, '2025-06-29 22:28:04', '2025-06-29 23:28:04');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (172, 'OFFICE', '0.00', '110000.00', '0.00', NULL, '110000.00', '0.00', 'hawala', 'Expense', NULL, NULL, 46, NULL, NULL, NULL, '2025-02-10', 1, NULL, 1, '2025-06-30 13:44:47', '2025-06-30 14:44:47');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (173, 'B', '100000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Income', NULL, 91, NULL, NULL, 39, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 13:20:57', '2025-07-01 14:20:57');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (174, 'NOTE', '50000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'staff', 'Expense', NULL, 91, NULL, NULL, 39, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 13:22:56', '2025-07-01 14:22:56');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (177, '', '40000.00', '909.09', '44.00', 'AED', '0.00', '909.09', 'hawala_staff', 'Income', NULL, 91, 46, NULL, 39, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:02:48', '2025-07-01 15:02:48');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (178, 'note du', '0.00', '50000.00', '0.00', NULL, '50000.00', '0.00', 'hawala', 'Expense', NULL, NULL, 46, NULL, NULL, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:03:58', '2025-07-01 15:03:58');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (179, '10k', '10000.00', '0.00', '0.00', NULL, '0.00', '0.00', 'loan', 'Expense', NULL, 91, NULL, 6, 39, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:18:20', '2025-07-01 15:18:20');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (180, '', '676.00', '0.00', '0.00', NULL, '0.00', '0.00', 'loan', 'Income', NULL, 91, NULL, 6, 10, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:20:02', '2025-07-01 15:20:02');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (181, '', '50000.00', '1136.36', '44.00', 'AED', '0.00', '1136.36', 'hawala_staff', 'Income', NULL, 91, 46, NULL, 39, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:25:53', '2025-07-01 15:25:53');
INSERT INTO `transactions` (`id`, `notes`, `birr`, `amount`, `rate`, `currency`, `debit`, `credit`, `transaction_type`, `type`, `destination`, `staff_id`, `hawala_id`, `loan_id`, `bank_id`, `description`, `date`, `created_by`, `updated_by`, `is_active`, `created_at`, `updated_at`) VALUES (182, 'office ', '0.00', '50000.00', '0.00', NULL, '50000.00', '0.00', 'hawala', 'Expense', NULL, NULL, 46, NULL, NULL, NULL, '2025-07-01', 1, NULL, 1, '2025-07-01 14:28:29', '2025-07-01 15:28:29');


#
# TABLE STRUCTURE FOR: users
#

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `dark_mode`) VALUES (1, '127.0.0.1', 'administrator', '$2y$10$EHEkQvViRUF95n4K5bdWiOrJbvbNwieIzBnht/DSHvV5AEta8uoKK', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1751371187, 1, 'Admin', 'istrator', 'ADMIN', '0', 0);
INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `dark_mode`) VALUES (2, '::1', NULL, '$2y$10$xsdHLVbB8GENMMD7L5pYM.qn9Q1Edo7dSVRfC.L.USGz/lpEJrmTW', 'admin82@admin.coms', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1743937904, 1744051913, 1, 'mustefa detamo', 'ahmed', 'nebat', '0912121212', 0);
INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `dark_mode`) VALUES (3, '::1', NULL, '$2y$10$F3SJJ5KhGNweM9fmmBZ1wukEV0c6g0tRElk9sfLDbetWAQNqO68Ua', 'admin82@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1745986540, 1751372575, 1, 'Mustefa', 'Detamo', 'n', '12345678', 1);


#
# TABLE STRUCTURE FOR: users_groups
#

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (35, 1, 1);
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (36, 1, 4);
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (37, 2, 4);
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (38, 3, 4);


