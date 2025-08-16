/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone1` varchar(255) DEFAULT NULL,
  `telephone2` varchar(255) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `vat_number` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `sort_code` varchar(255) DEFAULT NULL,
  `account_nr` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `swift` varchar(255) DEFAULT NULL,
  `disclaimer` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `home_phone` varchar(255) DEFAULT NULL,
  `work_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email1` varchar(255) NOT NULL,
  `email2` varchar(255) DEFAULT NULL,
  `email3` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `web_page` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `grand_design` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fullname_index` (`title`,`firstname`,`lastname`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dimensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dimensions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `width` int(11) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `thickness` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'plank',
  `price` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `width` (`width`,`length`,`thickness`,`type`,`price`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `details` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_order_id_foreign` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extras_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `floors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `floors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `floors_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grades_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) unsigned NOT NULL,
  `measurement_unit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_materials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `material_id` int(10) unsigned NOT NULL,
  `unit_price` decimal(7,2) unsigned NOT NULL,
  `quantity` decimal(7,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_materials_order_id_foreign` (`order_id`),
  KEY `order_materials_material_id_foreign` (`material_id`),
  CONSTRAINT `order_materials_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  CONSTRAINT `order_materials_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `quantity` decimal(7,2) unsigned NOT NULL,
  `unit_price` decimal(7,2) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_services_order_id_foreign` (`order_id`),
  KEY `order_services_service_id_foreign` (`service_id`),
  CONSTRAINT `order_services_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned DEFAULT NULL,
  `cc` varchar(255) NOT NULL,
  `mail_message` longtext NOT NULL,
  `proforma_message` longtext NOT NULL,
  `payment_terms` longtext NOT NULL,
  `notes` varchar(255) NOT NULL,
  `proforma` tinyint(1) NOT NULL,
  `sent` timestamp NULL DEFAULT NULL,
  `vat` decimal(4,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `date_due` timestamp NULL DEFAULT NULL,
  `due` int(11) NOT NULL DEFAULT 0,
  `due_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `payment_date` timestamp NULL DEFAULT NULL,
  `photo_request` timestamp NULL DEFAULT NULL,
  `review_request` timestamp NULL DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `vat_total` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `proforma_breakdown` tinyint(1) NOT NULL,
  `reverse_charge` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_company_id_foreign` (`company_id`),
  KEY `orders_status_id_foreign` (`status_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_project_id_foreign` (`project_id`),
  KEY `vat_index` (`vat`),
  CONSTRAINT `orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `orders_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `meterage` decimal(7,2) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_areas_area_id_product_id_unique` (`area_id`,`product_id`) USING BTREE,
  KEY `product_areas_product_id_foreign` (`product_id`),
  CONSTRAINT `product_areas_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `product_areas_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `floor_id` int(10) unsigned NOT NULL,
  `extra_id` int(10) unsigned DEFAULT NULL,
  `dimension_id` int(10) unsigned NOT NULL,
  `grade_id` int(10) unsigned NOT NULL,
  `discount` decimal(4,2) unsigned DEFAULT NULL,
  `wastage_rate` decimal(4,2) unsigned DEFAULT NULL,
  `meterage` decimal(6,2) unsigned DEFAULT NULL,
  `unit_price` decimal(6,2) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_order_id_foreign` (`order_id`),
  KEY `products_floor_id_foreign` (`floor_id`),
  KEY `products_extra_id_foreign` (`extra_id`),
  KEY `products_grade_id_foreign` (`grade_id`),
  KEY `products_dimension_id_foreign` (`dimension_id`),
  CONSTRAINT `products_dimension_id_foreign` FOREIGN KEY (`dimension_id`) REFERENCES `dimensions` (`id`),
  CONSTRAINT `products_extra_id_foreign` FOREIGN KEY (`extra_id`) REFERENCES `extras` (`id`),
  CONSTRAINT `products_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`),
  CONSTRAINT `products_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`),
  CONSTRAINT `products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `street` varchar(255) DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(7,2) unsigned NOT NULL,
  `measurement_unit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tax_deductions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_deductions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `ref` varchar(255) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_deductions_order_id_foreign` (`order_id`),
  CONSTRAINT `tax_deductions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_11_000000_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2016_10_24_152427_create_statuses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2016_10_25_165324_create_customers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2016_11_30_164529_create_grades_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2016_12_01_162111_create_dimensions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2016_12_01_162122_create_floors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2016_12_01_162133_create_extras_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2017_02_23_155232_create_areas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2017_02_26_182150_create_projects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2017_02_26_182151_create_companies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2017_02_26_182152_create_orders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2017_02_27_142151_create_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2017_02_27_155245_create_product_areas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2017_02_27_163846_create_materials_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2017_02_27_163847_create_order_materials_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2017_02_27_171320_create_services_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2017_02_27_171321_create_order_services_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2017_08_01_175122_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2017_09_14_114439_create_payments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2018_05_01_143312_add_grand_design_to_customers_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2018_06_22_160112_add_due_field_to_orders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2018_06_29_171621_add_last_payment_date_to_orders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2018_07_05_163013_add_grand_total_column_to_orders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2018_09_11_141440_add_extra_columns_to_orders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2018_10_09_151843_create_tax_deductions_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2018_10_16_172126_add_request_columns_to_orders',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2018_11_14_120713_create_invoices_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2019_10_15_121241_add_description_to_payments',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2019_10_15_134954_add_proforma_breakdown_to_orders_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2020_07_06_182555_create_expenses_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2022_04_22_164628_remove_date_field_from_expenses',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2022_07_08_150657_add_notes_field_to_companies_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2019_12_14_000001_create_personal_access_tokens_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2022_12_09_164948_add_reverse_charge_to_orders_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2023_08_24_153034_add_due_amount_to_orders_table',9);
