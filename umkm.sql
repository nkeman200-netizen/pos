/*
 Navicat Premium Dump SQL

 Source Server         : sems2
 Source Server Type    : MySQL
 Source Server Version : 80402 (8.4.2)
 Source Host           : localhost:3306
 Source Schema         : umkm

 Target Server Type    : MySQL
 Target Server Version : 80402 (8.4.2)
 File Encoding         : 65001

 Date: 21/04/2026 23:28:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` int NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json NULL,
  `new_values` json NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `audit_logs_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 126 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of audit_logs
-- ----------------------------
INSERT INTO `audit_logs` VALUES (1, NULL, 'created', 1, 'App\\Models\\User', NULL, '{\"id\": 1, \"name\": \"Bapak Owner\", \"role\": \"owner\", \"email\": \"owner@apotek.com\", \"password\": \"$2y$12$svANzKMLvdLu/dsU6KPGnuJ3Lg3RJH5.N0DYcJsuODOEw7IJAyrDe\", \"created_at\": \"2026-04-18 19:03:42\", \"updated_at\": \"2026-04-18 19:03:42\"}', '127.0.0.1', 'Symfony', '2026-04-18 19:03:43', '2026-04-18 19:03:43');
INSERT INTO `audit_logs` VALUES (2, NULL, 'created', 2, 'App\\Models\\User', NULL, '{\"id\": 2, \"name\": \"Apoteker Sofyan\", \"role\": \"admin\", \"email\": \"admin@apotek.com\", \"password\": \"$2y$12$vXei.pB7ouBgkh.sCf4Uo.wD7SBHX3LcGLYa22Axj7zcYuVCCqgpa\", \"created_at\": \"2026-04-18 19:03:43\", \"updated_at\": \"2026-04-18 19:03:43\"}', '127.0.0.1', 'Symfony', '2026-04-18 19:03:43', '2026-04-18 19:03:43');
INSERT INTO `audit_logs` VALUES (3, NULL, 'created', 3, 'App\\Models\\User', NULL, '{\"id\": 3, \"name\": \"Kasir Depan\", \"role\": \"kasir\", \"email\": \"kasir@apotek.com\", \"password\": \"$2y$12$QkLQanqMjhrHvTSqhrxJUekewjJWY8AVhnG1sqzr3a.cmVirQhVQu\", \"created_at\": \"2026-04-18 19:03:45\", \"updated_at\": \"2026-04-18 19:03:45\"}', '127.0.0.1', 'Symfony', '2026-04-18 19:03:45', '2026-04-18 19:03:45');
INSERT INTO `audit_logs` VALUES (4, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": null}', '{\"remember_token\": \"QlwrepmWxkroBcsu3wEBi219172XuBGZgYG3IE12Ar2zfSCwx3nRfhhbxIQH\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 20:43:21', '2026-04-18 20:43:21');
INSERT INTO `audit_logs` VALUES (5, NULL, 'created', 1, 'App\\Models\\Supplier', NULL, '{\"id\": 1, \"name\": \"akwafkw\", \"phone\": \"0888024242\", \"address\": \"cilacap\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (6, NULL, 'created', 1, 'App\\Models\\Category', NULL, '{\"id\": 1, \"name\": \"Obat Bebas\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (7, NULL, 'created', 2, 'App\\Models\\Category', NULL, '{\"id\": 2, \"name\": \"Obat Bebas Terbatas\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (8, NULL, 'created', 3, 'App\\Models\\Category', NULL, '{\"id\": 3, \"name\": \"Obat Keras\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (9, NULL, 'created', 4, 'App\\Models\\Category', NULL, '{\"id\": 4, \"name\": \"Vitamin & Suplemen\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (10, NULL, 'created', 5, 'App\\Models\\Category', NULL, '{\"id\": 5, \"name\": \"Alat Kesehatan\", \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (11, NULL, 'created', 1, 'App\\Models\\Unit', NULL, '{\"id\": 1, \"name\": \"Strip\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"str\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (12, NULL, 'created', 2, 'App\\Models\\Unit', NULL, '{\"id\": 2, \"name\": \"Botol\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"btl\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (13, NULL, 'created', 3, 'App\\Models\\Unit', NULL, '{\"id\": 3, \"name\": \"Tablet\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"tab\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (14, NULL, 'created', 4, 'App\\Models\\Unit', NULL, '{\"id\": 4, \"name\": \"Kapsul\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"kps\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (15, NULL, 'created', 5, 'App\\Models\\Unit', NULL, '{\"id\": 5, \"name\": \"Tube\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"tb\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (16, NULL, 'created', 6, 'App\\Models\\Unit', NULL, '{\"id\": 6, \"name\": \"Box\", \"created_at\": \"2026-04-18 21:14:01\", \"short_name\": \"box\", \"updated_at\": \"2026-04-18 21:14:01\"}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (17, NULL, 'created', 1, 'App\\Models\\Product', NULL, '{\"id\": 1, \"sku\": \"899123456001\", \"name\": \"Paracetamol 500mg\", \"unit_id\": 1, \"is_active\": true, \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\", \"category_id\": 1, \"selling_price\": 5000}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (18, NULL, 'created', 1, 'App\\Models\\ProductBatch', NULL, '{\"id\": 1, \"stock\": 50, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 1, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-A-3469\", \"expired_date\": \"2026-10-18\", \"purchase_price\": 3500}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (19, NULL, 'created', 2, 'App\\Models\\ProductBatch', NULL, '{\"id\": 2, \"stock\": 100, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 1, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-B-6098\", \"expired_date\": \"2027-04-18\", \"purchase_price\": 3750}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (20, NULL, 'created', 2, 'App\\Models\\Product', NULL, '{\"id\": 2, \"sku\": \"899123456002\", \"name\": \"Amoxicillin 500mg\", \"unit_id\": 1, \"is_active\": true, \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\", \"category_id\": 3, \"selling_price\": 12000}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (21, NULL, 'created', 3, 'App\\Models\\ProductBatch', NULL, '{\"id\": 3, \"stock\": 50, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 2, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-A-8248\", \"expired_date\": \"2026-10-18\", \"purchase_price\": 8400}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (22, NULL, 'created', 4, 'App\\Models\\ProductBatch', NULL, '{\"id\": 4, \"stock\": 100, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 2, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-B-4893\", \"expired_date\": \"2027-04-18\", \"purchase_price\": 9000}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (23, NULL, 'created', 3, 'App\\Models\\Product', NULL, '{\"id\": 3, \"sku\": \"899123456003\", \"name\": \"Imboost Force Sirup 60ml\", \"unit_id\": 2, \"is_active\": true, \"created_at\": \"2026-04-18 21:14:01\", \"updated_at\": \"2026-04-18 21:14:01\", \"category_id\": 4, \"selling_price\": 45000}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (24, NULL, 'created', 5, 'App\\Models\\ProductBatch', NULL, '{\"id\": 5, \"stock\": 50, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 3, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-A-8993\", \"expired_date\": \"2026-10-18\", \"purchase_price\": 31499.999999999996}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (25, NULL, 'created', 6, 'App\\Models\\ProductBatch', NULL, '{\"id\": 6, \"stock\": 100, \"created_at\": \"2026-04-18 21:14:01\", \"product_id\": 3, \"updated_at\": \"2026-04-18 21:14:01\", \"batch_number\": \"BATCH-B-1876\", \"expired_date\": \"2027-04-18\", \"purchase_price\": 33750}', '127.0.0.1', 'Symfony', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `audit_logs` VALUES (26, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"QlwrepmWxkroBcsu3wEBi219172XuBGZgYG3IE12Ar2zfSCwx3nRfhhbxIQH\"}', '{\"remember_token\": \"j5aTtrYnWRq9JhvACuODGDT802g8rey9bkQSGjHDZPWKv8840MwBTsegjBHG\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:08:31', '2026-04-19 00:08:31');
INSERT INTO `audit_logs` VALUES (27, 2, 'updated', 4, 'App\\Models\\ProductBatch', '{\"stock\": 100}', '{\"stock\": 90}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:09:47', '2026-04-19 00:09:47');
INSERT INTO `audit_logs` VALUES (28, 2, 'created', 1, 'App\\Models\\StockAdjustment', NULL, '{\"id\": 1, \"reason\": \"Barang Hilang\", \"user_id\": 2, \"created_at\": \"2026-04-19 00:10:24\", \"difference\": -5, \"product_id\": \"2\", \"system_qty\": 90, \"updated_at\": \"2026-04-19 00:10:24\", \"physical_qty\": \"85\", \"product_batch_id\": \"4\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:10:24', '2026-04-19 00:10:24');
INSERT INTO `audit_logs` VALUES (29, 2, 'updated', 4, 'App\\Models\\ProductBatch', '{\"stock\": 90, \"updated_at\": \"2026-04-18T17:09:47.000000Z\"}', '{\"stock\": \"85\", \"updated_at\": \"2026-04-19 00:10:24\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:10:24', '2026-04-19 00:10:24');
INSERT INTO `audit_logs` VALUES (30, 2, 'created', 1, 'App\\Models\\Customer', NULL, '{\"id\": 1, \"name\": \"SOFYAN YUNUS ROHMAN\", \"phone\": \"0895341935848\", \"address\": \"Jl. kunci desa sudagaran kec. Sidareja, Cilacap, Jawa tengah\", \"created_at\": \"2026-04-19 00:10:40\", \"updated_at\": \"2026-04-19 00:10:40\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:10:40', '2026-04-19 00:10:40');
INSERT INTO `audit_logs` VALUES (31, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"j5aTtrYnWRq9JhvACuODGDT802g8rey9bkQSGjHDZPWKv8840MwBTsegjBHG\"}', '{\"remember_token\": \"F7yi3ga6xGZ07bLkQqNlE4k2F7j7NmFmZAlDkW6FzdD25c0ZIjSRsAcq6Ys1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:11:03', '2026-04-19 00:11:03');
INSERT INTO `audit_logs` VALUES (32, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"F7yi3ga6xGZ07bLkQqNlE4k2F7j7NmFmZAlDkW6FzdD25c0ZIjSRsAcq6Ys1\"}', '{\"remember_token\": \"l5HLcRPjT2JCEVkETTisTxUMCEmFHg5kaLP3Dh3MCXxGslXAFWgWGixPLIjJ\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:11:37', '2026-04-19 00:11:37');
INSERT INTO `audit_logs` VALUES (33, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"l5HLcRPjT2JCEVkETTisTxUMCEmFHg5kaLP3Dh3MCXxGslXAFWgWGixPLIjJ\"}', '{\"remember_token\": \"w0KyjTVGh1L2jvKMlVj5opz5KOqtckjnO8vaHQdNysanizWQoXJihHC9NTxa\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 00:15:18', '2026-04-19 00:15:18');
INSERT INTO `audit_logs` VALUES (34, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"w0KyjTVGh1L2jvKMlVj5opz5KOqtckjnO8vaHQdNysanizWQoXJihHC9NTxa\"}', '{\"remember_token\": \"Ud91taRJ7vj4mQDTtoBSOLU4LptRv7P5pffndmWXjah8f8B83aVYsEgYSMfl\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 01:07:13', '2026-04-19 01:07:13');
INSERT INTO `audit_logs` VALUES (35, 2, 'created', 2, 'App\\Models\\Supplier', NULL, '{\"id\": 2, \"name\": \"adacv\", \"phone\": \"4125\", \"address\": \"ASVESDEFQ\\n\", \"created_at\": \"2026-04-19 11:44:45\", \"updated_at\": \"2026-04-19 11:44:45\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 11:44:45', '2026-04-19 11:44:45');
INSERT INTO `audit_logs` VALUES (36, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 50}', '{\"stock\": 45}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:27:10', '2026-04-19 13:27:10');
INSERT INTO `audit_logs` VALUES (37, 2, 'created', 2, 'App\\Models\\StockAdjustment', NULL, '{\"id\": 2, \"reason\": \"Koreksi Salah Input\", \"user_id\": 2, \"created_at\": \"2026-04-19 13:28:44\", \"difference\": 5, \"product_id\": \"2\", \"system_qty\": 45, \"updated_at\": \"2026-04-19 13:28:44\", \"physical_qty\": \"50\", \"product_batch_id\": \"3\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:28:44', '2026-04-19 13:28:44');
INSERT INTO `audit_logs` VALUES (38, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 45, \"updated_at\": \"2026-04-19T06:27:10.000000Z\"}', '{\"stock\": \"50\", \"updated_at\": \"2026-04-19 13:28:44\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:28:44', '2026-04-19 13:28:44');
INSERT INTO `audit_logs` VALUES (39, 2, 'created', 2, 'App\\Models\\Customer', NULL, '{\"id\": 2, \"name\": \"SOFYAN YUNUS ROHMAN\", \"phone\": \"0895341935848\", \"address\": \"Jl. kunci desa sudagaran kec. Sidareja, Cilacap, Jawa tengah\", \"created_at\": \"2026-04-19 13:29:32\", \"updated_at\": \"2026-04-19 13:29:32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:29:32', '2026-04-19 13:29:32');
INSERT INTO `audit_logs` VALUES (40, 2, 'created', 3, 'App\\Models\\Supplier', NULL, '{\"id\": 3, \"name\": \"apoopopop\", \"phone\": \"529418259829\", \"address\": \"fwefailwehfaewbvawebuawueb\\n\", \"created_at\": \"2026-04-19 13:35:45\", \"updated_at\": \"2026-04-19 13:35:45\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:35:45', '2026-04-19 13:35:45');
INSERT INTO `audit_logs` VALUES (41, 2, 'updated', 3, 'App\\Models\\Supplier', '{\"phone\": \"529418259829\", \"updated_at\": \"2026-04-19T06:35:45.000000Z\"}', '{\"phone\": \"5294182598294213512512\", \"updated_at\": \"2026-04-19 13:35:52\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:35:52', '2026-04-19 13:35:52');
INSERT INTO `audit_logs` VALUES (42, 2, 'created', 4, 'App\\Models\\Product', NULL, '{\"id\": 4, \"sku\": \"fawefa\", \"name\": \"vsdaavrgw\", \"unit_id\": \"2\", \"is_active\": true, \"created_at\": \"2026-04-19 13:36:18\", \"updated_at\": \"2026-04-19 13:36:18\", \"category_id\": \"2\", \"selling_price\": \"35321513\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:36:18', '2026-04-19 13:36:18');
INSERT INTO `audit_logs` VALUES (43, 2, 'created', 6, 'App\\Models\\Category', NULL, '{\"id\": 6, \"name\": \"efeawfef\", \"created_at\": \"2026-04-19 13:37:41\", \"updated_at\": \"2026-04-19 13:37:41\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:37:41', '2026-04-19 13:37:41');
INSERT INTO `audit_logs` VALUES (44, 2, 'updated', 6, 'App\\Models\\Category', '{\"name\": \"efeawfef\", \"updated_at\": \"2026-04-19T06:37:41.000000Z\"}', '{\"name\": \"efeawfef314r3 fr\", \"updated_at\": \"2026-04-19 13:37:47\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:37:47', '2026-04-19 13:37:47');
INSERT INTO `audit_logs` VALUES (45, 2, 'created', 7, 'App\\Models\\Category', NULL, '{\"id\": 7, \"name\": \"afdaf\", \"created_at\": \"2026-04-19 14:15:06\", \"updated_at\": \"2026-04-19 14:15:06\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:15:06', '2026-04-19 14:15:06');
INSERT INTO `audit_logs` VALUES (46, 2, 'updated', 7, 'App\\Models\\Category', '{\"name\": \"afdaf\", \"updated_at\": \"2026-04-19T07:15:06.000000Z\"}', '{\"name\": \"afdafafdsfad\", \"updated_at\": \"2026-04-19 14:15:12\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:15:12', '2026-04-19 14:15:12');
INSERT INTO `audit_logs` VALUES (47, 2, 'created', 1, 'App\\Models\\Purchase', NULL, '{\"id\": 1, \"user_id\": 2, \"created_at\": \"2026-04-19 14:35:21\", \"total_cost\": 10222, \"updated_at\": \"2026-04-19 14:35:21\", \"supplier_id\": \"3\", \"purchase_date\": {\"date\": \"2026-04-19 14:35:21.097690\", \"timezone\": \"Asia/Jakarta\", \"timezone_type\": 3}, \"purchase_number\": \"PRC-20260419143521\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:35:21', '2026-04-19 14:35:21');
INSERT INTO `audit_logs` VALUES (48, 2, 'created', 1, 'App\\Models\\PurchaseItem', NULL, '{\"id\": 1, \"quantity\": 1, \"subtotal\": 10222, \"created_at\": \"2026-04-19 14:35:21\", \"product_id\": 1, \"updated_at\": \"2026-04-19 14:35:21\", \"purchase_id\": 1, \"purchase_price\": \"10222\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:35:21', '2026-04-19 14:35:21');
INSERT INTO `audit_logs` VALUES (49, 2, 'created', 7, 'App\\Models\\ProductBatch', NULL, '{\"id\": 7, \"stock\": 1, \"created_at\": \"2026-04-19 14:35:21\", \"product_id\": 1, \"updated_at\": \"2026-04-19 14:35:21\", \"batch_number\": \"4234\", \"expired_date\": \"2026-05-09\", \"purchase_price\": \"10222\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:35:21', '2026-04-19 14:35:21');
INSERT INTO `audit_logs` VALUES (50, 2, 'created', 1, 'App\\Models\\PurchaseOrder', NULL, '{\"id\": 1, \"notes\": \"\", \"status\": \"pending\", \"user_id\": 2, \"po_number\": \"PO-20260419143615\", \"created_at\": \"2026-04-19 14:36:15\", \"order_date\": \"2026-04-19\", \"updated_at\": \"2026-04-19 14:36:15\", \"supplier_id\": \"2\", \"total_amount\": 11110, \"expected_date\": \"2026-05-07\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:36:15', '2026-04-19 14:36:15');
INSERT INTO `audit_logs` VALUES (51, 2, 'created', 1, 'App\\Models\\PurchaseOrderItem', NULL, '{\"id\": 1, \"quantity\": 1, \"subtotal\": 11110, \"created_at\": \"2026-04-19 14:36:15\", \"product_id\": 1, \"updated_at\": \"2026-04-19 14:36:15\", \"purchase_price\": \"11110\", \"purchase_order_id\": 1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:36:15', '2026-04-19 14:36:15');
INSERT INTO `audit_logs` VALUES (52, 2, 'created', 1, 'App\\Models\\Sale', NULL, '{\"id\": 1, \"user_id\": 2, \"kembalian\": 5000, \"created_at\": \"2026-04-19 15:06:21\", \"pembayaran\": 20000, \"updated_at\": \"2026-04-19 15:06:21\", \"customer_id\": null, \"total_price\": 15000, \"invoice_number\": \"INV-260419150621\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `audit_logs` VALUES (53, 2, 'created', 1, 'App\\Models\\SaleItem', NULL, '{\"id\": 1, \"sale_id\": 1, \"quantity\": 3, \"subtotal\": 15000, \"created_at\": \"2026-04-19 15:06:21\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-19 15:06:21\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `audit_logs` VALUES (54, 2, 'updated', 7, 'App\\Models\\ProductBatch', '{\"stock\": 1, \"updated_at\": \"2026-04-19T07:35:21.000000Z\"}', '{\"stock\": 0, \"updated_at\": \"2026-04-19 15:06:21\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `audit_logs` VALUES (55, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 50}', '{\"stock\": 48}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `audit_logs` VALUES (56, 2, 'created', 2, 'App\\Models\\Sale', NULL, '{\"id\": 2, \"user_id\": 2, \"kembalian\": 2288525, \"created_at\": \"2026-04-19 23:48:02\", \"pembayaran\": 2343525, \"updated_at\": \"2026-04-19 23:48:02\", \"customer_id\": null, \"total_price\": 55000, \"invoice_number\": \"INV-260419234802\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 23:48:02', '2026-04-19 23:48:02');
INSERT INTO `audit_logs` VALUES (57, 2, 'created', 2, 'App\\Models\\SaleItem', NULL, '{\"id\": 2, \"sale_id\": 2, \"quantity\": 11, \"subtotal\": 55000, \"created_at\": \"2026-04-19 23:48:02\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-19 23:48:02\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 23:48:02', '2026-04-19 23:48:02');
INSERT INTO `audit_logs` VALUES (58, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 48}', '{\"stock\": 37}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 23:48:02', '2026-04-19 23:48:02');
INSERT INTO `audit_logs` VALUES (59, 2, 'created', 3, 'App\\Models\\Sale', NULL, '{\"id\": 3, \"user_id\": 2, \"kembalian\": 20000, \"created_at\": \"2026-04-20 06:50:08\", \"pembayaran\": 380000, \"updated_at\": \"2026-04-20 06:50:08\", \"customer_id\": null, \"total_price\": 360000, \"invoice_number\": \"INV-260420065008\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:50:08', '2026-04-20 06:50:08');
INSERT INTO `audit_logs` VALUES (60, 2, 'created', 3, 'App\\Models\\SaleItem', NULL, '{\"id\": 3, \"sale_id\": 3, \"quantity\": 8, \"subtotal\": 360000, \"created_at\": \"2026-04-20 06:50:08\", \"product_id\": 3, \"unit_price\": 45000, \"updated_at\": \"2026-04-20 06:50:08\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:50:08', '2026-04-20 06:50:08');
INSERT INTO `audit_logs` VALUES (61, 2, 'updated', 5, 'App\\Models\\ProductBatch', '{\"stock\": 50}', '{\"stock\": 42}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:50:08', '2026-04-20 06:50:08');
INSERT INTO `audit_logs` VALUES (62, 2, 'created', 4, 'App\\Models\\Sale', NULL, '{\"id\": 4, \"user_id\": 2, \"kembalian\": 4000, \"created_at\": \"2026-04-20 06:53:58\", \"pembayaran\": 40000, \"updated_at\": \"2026-04-20 06:53:58\", \"customer_id\": null, \"total_price\": 36000, \"invoice_number\": \"INV-260420065358\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:53:58', '2026-04-20 06:53:58');
INSERT INTO `audit_logs` VALUES (63, 2, 'created', 4, 'App\\Models\\SaleItem', NULL, '{\"id\": 4, \"sale_id\": 4, \"quantity\": 3, \"subtotal\": 36000, \"created_at\": \"2026-04-20 06:53:58\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-20 06:53:58\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:53:58', '2026-04-20 06:53:58');
INSERT INTO `audit_logs` VALUES (64, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 50}', '{\"stock\": 47}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:53:58', '2026-04-20 06:53:58');
INSERT INTO `audit_logs` VALUES (65, 2, 'created', 5, 'App\\Models\\Sale', NULL, '{\"id\": 5, \"user_id\": 2, \"kembalian\": 20000, \"created_at\": \"2026-04-20 07:06:32\", \"pembayaran\": 200000, \"updated_at\": \"2026-04-20 07:06:32\", \"customer_id\": null, \"total_price\": 180000, \"invoice_number\": \"INV-260420070632\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 07:06:32', '2026-04-20 07:06:32');
INSERT INTO `audit_logs` VALUES (66, 2, 'created', 5, 'App\\Models\\SaleItem', NULL, '{\"id\": 5, \"sale_id\": 5, \"quantity\": 4, \"subtotal\": 180000, \"created_at\": \"2026-04-20 07:06:32\", \"product_id\": 3, \"unit_price\": 45000, \"updated_at\": \"2026-04-20 07:06:32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 07:06:32', '2026-04-20 07:06:32');
INSERT INTO `audit_logs` VALUES (67, 2, 'updated', 5, 'App\\Models\\ProductBatch', '{\"stock\": 42}', '{\"stock\": 38}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 07:06:32', '2026-04-20 07:06:32');
INSERT INTO `audit_logs` VALUES (68, 2, 'created', 6, 'App\\Models\\Sale', NULL, '{\"id\": 6, \"user_id\": 2, \"kembalian\": 58000, \"created_at\": \"2026-04-20 10:38:24\", \"pembayaran\": 700000, \"updated_at\": \"2026-04-20 10:38:24\", \"customer_id\": null, \"total_price\": 642000, \"invoice_number\": \"INV-260420103824\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (69, 2, 'created', 6, 'App\\Models\\SaleItem', NULL, '{\"id\": 6, \"sale_id\": 6, \"quantity\": 12, \"subtotal\": 540000, \"created_at\": \"2026-04-20 10:38:24\", \"product_id\": 3, \"unit_price\": 45000, \"updated_at\": \"2026-04-20 10:38:24\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (70, 2, 'updated', 5, 'App\\Models\\ProductBatch', '{\"stock\": 38}', '{\"stock\": 26}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (71, 2, 'created', 7, 'App\\Models\\SaleItem', NULL, '{\"id\": 7, \"sale_id\": 6, \"quantity\": 6, \"subtotal\": 72000, \"created_at\": \"2026-04-20 10:38:24\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-20 10:38:24\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (72, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 47}', '{\"stock\": 41}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (73, 2, 'created', 8, 'App\\Models\\SaleItem', NULL, '{\"id\": 8, \"sale_id\": 6, \"quantity\": 6, \"subtotal\": 30000, \"created_at\": \"2026-04-20 10:38:24\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-20 10:38:24\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (74, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 37}', '{\"stock\": 31}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `audit_logs` VALUES (75, 3, 'created', 7, 'App\\Models\\Sale', NULL, '{\"id\": 7, \"user_id\": 3, \"kembalian\": 5000, \"created_at\": \"2026-04-20 11:06:16\", \"pembayaran\": 50000, \"updated_at\": \"2026-04-20 11:06:16\", \"customer_id\": null, \"total_price\": 45000, \"invoice_number\": \"INV-260420110616\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 11:06:16', '2026-04-20 11:06:16');
INSERT INTO `audit_logs` VALUES (76, 3, 'created', 9, 'App\\Models\\SaleItem', NULL, '{\"id\": 9, \"sale_id\": 7, \"quantity\": 1, \"subtotal\": 45000, \"created_at\": \"2026-04-20 11:06:16\", \"product_id\": 3, \"unit_price\": 45000, \"updated_at\": \"2026-04-20 11:06:16\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 11:06:16', '2026-04-20 11:06:16');
INSERT INTO `audit_logs` VALUES (77, 3, 'updated', 5, 'App\\Models\\ProductBatch', '{\"stock\": 26}', '{\"stock\": 25}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 11:06:16', '2026-04-20 11:06:16');
INSERT INTO `audit_logs` VALUES (78, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"Ud91taRJ7vj4mQDTtoBSOLU4LptRv7P5pffndmWXjah8f8B83aVYsEgYSMfl\"}', '{\"remember_token\": \"ms1mD93s8jgamWnuio0EjH2DtsNktQoq1otaLNh6DZSqUMXC3vdKdrhpJUEf\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 12:08:29', '2026-04-20 12:08:29');
INSERT INTO `audit_logs` VALUES (79, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"ms1mD93s8jgamWnuio0EjH2DtsNktQoq1otaLNh6DZSqUMXC3vdKdrhpJUEf\"}', '{\"remember_token\": \"AQBf7D0mRK6aopOrfITf9dhji9CM9Ae7ZKT7WKMh8kk7t3t9r7r9tV8YgIqR\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 12:17:57', '2026-04-20 12:17:57');
INSERT INTO `audit_logs` VALUES (80, 1, 'updated', 1, 'App\\Models\\User', '{\"remember_token\": \"AQBf7D0mRK6aopOrfITf9dhji9CM9Ae7ZKT7WKMh8kk7t3t9r7r9tV8YgIqR\"}', '{\"remember_token\": \"6N8czdSCMH9P49ju994zUitT97XosIy9gvnkJFNPzC28akriPhe3R6dVaqnr\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 12:28:11', '2026-04-20 12:28:11');
INSERT INTO `audit_logs` VALUES (81, 2, 'updated', 6, 'App\\Models\\ProductBatch', '{\"stock\": 100}', '{\"stock\": 101}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 19:33:06', '2026-04-20 19:33:06');
INSERT INTO `audit_logs` VALUES (82, 2, 'updated', 6, 'App\\Models\\ProductBatch', '{\"stock\": 101}', '{\"stock\": 102}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 19:47:04', '2026-04-20 19:47:04');
INSERT INTO `audit_logs` VALUES (83, 2, 'updated', 7, 'App\\Models\\Sale', '{\"status\": \"completed\", \"updated_at\": \"2026-04-20T04:06:16.000000Z\", \"void_reason\": null}', '{\"status\": \"void\", \"updated_at\": \"2026-04-20 22:18:19\", \"void_reason\": \"tes void (Oleh: Apoteker Sofyan)\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 22:18:19', '2026-04-20 22:18:19');
INSERT INTO `audit_logs` VALUES (84, 2, 'updated', 6, 'App\\Models\\ProductBatch', '{\"stock\": 102}', '{\"stock\": 103}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 22:18:19', '2026-04-20 22:18:19');
INSERT INTO `audit_logs` VALUES (85, 2, 'created', 5, 'App\\Models\\Product', NULL, '{\"id\": 5, \"sku\": \"afafasfasd\", \"name\": \"cscasdfe\", \"unit_id\": \"3\", \"is_active\": true, \"created_at\": \"2026-04-21 07:23:33\", \"updated_at\": \"2026-04-21 07:23:33\", \"category_id\": \"3\", \"selling_price\": 241341}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 07:23:33', '2026-04-21 07:23:33');
INSERT INTO `audit_logs` VALUES (86, 2, 'updated', 5, 'App\\Models\\Product', '{\"updated_at\": \"2026-04-21T00:23:33.000000Z\", \"selling_price\": 241341}', '{\"updated_at\": \"2026-04-21 07:27:33\", \"selling_price\": 200000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 07:27:33', '2026-04-21 07:27:33');
INSERT INTO `audit_logs` VALUES (87, 2, 'updated', 5, 'App\\Models\\Product', '{\"updated_at\": \"2026-04-21T00:27:33.000000Z\", \"selling_price\": 200000}', '{\"updated_at\": \"2026-04-21 07:47:20\", \"selling_price\": 2000000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 07:47:20', '2026-04-21 07:47:20');
INSERT INTO `audit_logs` VALUES (88, 2, 'created', 8, 'App\\Models\\Sale', NULL, '{\"id\": 8, \"user_id\": 2, \"kembalian\": 0, \"created_at\": \"2026-04-21 08:51:13\", \"pembayaran\": 22000, \"updated_at\": \"2026-04-21 08:51:13\", \"customer_id\": null, \"total_price\": 22000, \"invoice_number\": \"INV-260421085113\", \"payment_method\": \"qris\", \"payment_reference\": \"79685896\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `audit_logs` VALUES (89, 2, 'created', 10, 'App\\Models\\SaleItem', NULL, '{\"id\": 10, \"sale_id\": 8, \"quantity\": 1, \"subtotal\": 12000, \"created_at\": \"2026-04-21 08:51:13\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-21 08:51:13\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `audit_logs` VALUES (90, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 41}', '{\"stock\": 40}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `audit_logs` VALUES (91, 2, 'created', 11, 'App\\Models\\SaleItem', NULL, '{\"id\": 11, \"sale_id\": 8, \"quantity\": 2, \"subtotal\": 10000, \"created_at\": \"2026-04-21 08:51:13\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 08:51:13\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `audit_logs` VALUES (92, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 31}', '{\"stock\": 29}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `audit_logs` VALUES (93, 2, 'created', 9, 'App\\Models\\Sale', NULL, '{\"id\": 9, \"user_id\": 2, \"kembalian\": 0, \"created_at\": \"2026-04-21 12:49:36\", \"pembayaran\": 5000, \"updated_at\": \"2026-04-21 12:49:36\", \"customer_id\": null, \"total_price\": 5000, \"invoice_number\": \"INV-260421124936\", \"payment_method\": \"qris\", \"payment_reference\": \"QRIS-20260421124936\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 12:49:36', '2026-04-21 12:49:36');
INSERT INTO `audit_logs` VALUES (94, 2, 'created', 12, 'App\\Models\\SaleItem', NULL, '{\"id\": 12, \"sale_id\": 9, \"quantity\": 1, \"subtotal\": 5000, \"created_at\": \"2026-04-21 12:49:36\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 12:49:36\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 12:49:36', '2026-04-21 12:49:36');
INSERT INTO `audit_logs` VALUES (95, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 29}', '{\"stock\": 28}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 12:49:36', '2026-04-21 12:49:36');
INSERT INTO `audit_logs` VALUES (96, 2, 'created', 10, 'App\\Models\\Sale', NULL, '{\"id\": 10, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 0, \"created_at\": \"2026-04-21 15:46:13\", \"pembayaran\": 5000, \"updated_at\": \"2026-04-21 15:46:13\", \"customer_id\": null, \"total_price\": 5000, \"invoice_number\": \"INV-260421154613\", \"payment_method\": \"qris\", \"payment_reference\": \"QR69E739557AA3C\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 15:46:13', '2026-04-21 15:46:13');
INSERT INTO `audit_logs` VALUES (97, 2, 'created', 13, 'App\\Models\\SaleItem', NULL, '{\"id\": 13, \"sale_id\": 10, \"quantity\": 1, \"subtotal\": 5000, \"created_at\": \"2026-04-21 15:46:13\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 15:46:13\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 15:46:13', '2026-04-21 15:46:13');
INSERT INTO `audit_logs` VALUES (98, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 28}', '{\"stock\": 27}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 15:46:13', '2026-04-21 15:46:13');
INSERT INTO `audit_logs` VALUES (99, 2, 'created', 11, 'App\\Models\\Sale', NULL, '{\"id\": 11, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 0, \"created_at\": \"2026-04-21 18:04:04\", \"pembayaran\": 83000, \"updated_at\": \"2026-04-21 18:04:04\", \"customer_id\": null, \"total_price\": 83000, \"invoice_number\": \"INV-260421180404\", \"payment_method\": \"qris\", \"payment_reference\": \"QR69E759A43343F\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `audit_logs` VALUES (100, 2, 'created', 14, 'App\\Models\\SaleItem', NULL, '{\"id\": 14, \"sale_id\": 11, \"quantity\": 7, \"subtotal\": 35000, \"created_at\": \"2026-04-21 18:04:04\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 18:04:04\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `audit_logs` VALUES (101, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 27}', '{\"stock\": 20}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `audit_logs` VALUES (102, 2, 'created', 15, 'App\\Models\\SaleItem', NULL, '{\"id\": 15, \"sale_id\": 11, \"quantity\": 4, \"subtotal\": 48000, \"created_at\": \"2026-04-21 18:04:04\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-21 18:04:04\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `audit_logs` VALUES (103, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 40}', '{\"stock\": 36}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `audit_logs` VALUES (104, 2, 'updated', 8, 'App\\Models\\Sale', '{\"status\": \"completed\", \"updated_at\": \"2026-04-21T01:51:13.000000Z\", \"void_reason\": null}', '{\"status\": \"void\", \"updated_at\": \"2026-04-21 18:08:05\", \"void_reason\": \"test void (Oleh: Apoteker Sofyan)\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:08:05', '2026-04-21 18:08:05');
INSERT INTO `audit_logs` VALUES (105, 2, 'updated', 4, 'App\\Models\\ProductBatch', '{\"stock\": 85}', '{\"stock\": 86}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:08:05', '2026-04-21 18:08:05');
INSERT INTO `audit_logs` VALUES (106, 2, 'updated', 2, 'App\\Models\\ProductBatch', '{\"stock\": 100}', '{\"stock\": 102}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:08:05', '2026-04-21 18:08:05');
INSERT INTO `audit_logs` VALUES (107, 2, 'created', 13, 'App\\Models\\Sale', NULL, '{\"id\": 13, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 3000, \"created_at\": \"2026-04-21 18:30:16\", \"pembayaran\": 40000, \"updated_at\": \"2026-04-21 18:30:16\", \"customer_id\": null, \"total_price\": 37000, \"invoice_number\": \"INV-260421183016\", \"payment_method\": \"cash\", \"payment_reference\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `audit_logs` VALUES (108, 2, 'created', 16, 'App\\Models\\SaleItem', NULL, '{\"id\": 16, \"sale_id\": 13, \"quantity\": 5, \"subtotal\": 25000, \"created_at\": \"2026-04-21 18:30:16\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 18:30:16\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `audit_logs` VALUES (109, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 20}', '{\"stock\": 15}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `audit_logs` VALUES (110, 2, 'created', 17, 'App\\Models\\SaleItem', NULL, '{\"id\": 17, \"sale_id\": 13, \"quantity\": 1, \"subtotal\": 12000, \"created_at\": \"2026-04-21 18:30:16\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-21 18:30:16\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `audit_logs` VALUES (111, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 36}', '{\"stock\": 35}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `audit_logs` VALUES (112, 2, 'created', 14, 'App\\Models\\Sale', NULL, '{\"id\": 14, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 1000, \"created_at\": \"2026-04-21 18:40:25\", \"pembayaran\": 6000, \"updated_at\": \"2026-04-21 18:40:25\", \"customer_id\": null, \"total_price\": 5000, \"invoice_number\": \"INV-260421184025\", \"payment_method\": \"cash\", \"payment_reference\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:40:25', '2026-04-21 18:40:25');
INSERT INTO `audit_logs` VALUES (113, 2, 'created', 18, 'App\\Models\\SaleItem', NULL, '{\"id\": 18, \"sale_id\": 14, \"quantity\": 1, \"subtotal\": 5000, \"created_at\": \"2026-04-21 18:40:25\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 18:40:25\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:40:25', '2026-04-21 18:40:25');
INSERT INTO `audit_logs` VALUES (114, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 15}', '{\"stock\": 14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 18:40:25', '2026-04-21 18:40:25');
INSERT INTO `audit_logs` VALUES (115, 2, 'updated', 2, 'App\\Models\\User', '{\"pin\": \"222222\", \"updated_at\": \"2026-04-18T12:03:43.000000Z\"}', '{\"pin\": \"444444\", \"updated_at\": \"2026-04-21 21:35:12\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 21:35:12', '2026-04-21 21:35:12');
INSERT INTO `audit_logs` VALUES (116, 2, 'updated', 2, 'App\\Models\\User', '{\"pin\": \"444444\", \"updated_at\": \"2026-04-21T14:35:12.000000Z\"}', '{\"pin\": \"222222\", \"updated_at\": \"2026-04-21 21:35:28\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 21:35:28', '2026-04-21 21:35:28');
INSERT INTO `audit_logs` VALUES (117, 2, 'created', 15, 'App\\Models\\Sale', NULL, '{\"id\": 15, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 293333, \"created_at\": \"2026-04-21 22:20:43\", \"pembayaran\": 333333, \"updated_at\": \"2026-04-21 22:20:43\", \"customer_id\": null, \"total_price\": 40000, \"invoice_number\": \"INV-260421222043\", \"payment_method\": \"cash\", \"payment_reference\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:20:43', '2026-04-21 22:20:43');
INSERT INTO `audit_logs` VALUES (118, 2, 'created', 19, 'App\\Models\\SaleItem', NULL, '{\"id\": 19, \"sale_id\": 15, \"quantity\": 8, \"subtotal\": 40000, \"created_at\": \"2026-04-21 22:20:43\", \"product_id\": 1, \"unit_price\": 5000, \"updated_at\": \"2026-04-21 22:20:43\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:20:43', '2026-04-21 22:20:43');
INSERT INTO `audit_logs` VALUES (119, 2, 'updated', 1, 'App\\Models\\ProductBatch', '{\"stock\": 14}', '{\"stock\": 6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:20:43', '2026-04-21 22:20:43');
INSERT INTO `audit_logs` VALUES (120, 2, 'created', 16, 'App\\Models\\Sale', NULL, '{\"id\": 16, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 0, \"created_at\": \"2026-04-21 22:23:54\", \"pembayaran\": 12000, \"updated_at\": \"2026-04-21 22:23:54\", \"customer_id\": null, \"total_price\": 12000, \"invoice_number\": \"INV-260421222354\", \"payment_method\": \"qris\", \"payment_reference\": \"QR69E7968A204B1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:23:54', '2026-04-21 22:23:54');
INSERT INTO `audit_logs` VALUES (121, 2, 'created', 20, 'App\\Models\\SaleItem', NULL, '{\"id\": 20, \"sale_id\": 16, \"quantity\": 1, \"subtotal\": 12000, \"created_at\": \"2026-04-21 22:23:54\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-21 22:23:54\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:23:54', '2026-04-21 22:23:54');
INSERT INTO `audit_logs` VALUES (122, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 35}', '{\"stock\": 34}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:23:54', '2026-04-21 22:23:54');
INSERT INTO `audit_logs` VALUES (123, 2, 'created', 17, 'App\\Models\\Sale', NULL, '{\"id\": 17, \"status\": \"completed\", \"user_id\": 2, \"kembalian\": 3000, \"created_at\": \"2026-04-21 22:24:21\", \"pembayaran\": 15000, \"updated_at\": \"2026-04-21 22:24:21\", \"customer_id\": null, \"total_price\": 12000, \"invoice_number\": \"INV-260421222421\", \"payment_method\": \"cash\", \"payment_reference\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:24:21', '2026-04-21 22:24:21');
INSERT INTO `audit_logs` VALUES (124, 2, 'created', 21, 'App\\Models\\SaleItem', NULL, '{\"id\": 21, \"sale_id\": 17, \"quantity\": 1, \"subtotal\": 12000, \"created_at\": \"2026-04-21 22:24:21\", \"product_id\": 2, \"unit_price\": 12000, \"updated_at\": \"2026-04-21 22:24:21\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:24:21', '2026-04-21 22:24:21');
INSERT INTO `audit_logs` VALUES (125, 2, 'updated', 3, 'App\\Models\\ProductBatch', '{\"stock\": 34}', '{\"stock\": 33}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 22:24:21', '2026-04-21 22:24:21');

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('laravel-cache-boost.roster.scan', 'a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:13:{i:0;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.54.1\";s:6:\"\0*\0dev\";b:0;}i:1;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"v0.3.14\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:6:\"0.3.14\";s:6:\"\0*\0dev\";b:0;}i:2;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^4.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:SANCTUM\";s:14:\"\0*\0packageName\";s:15:\"laravel/sanctum\";s:10:\"\0*\0version\";s:5:\"4.3.1\";s:6:\"\0*\0dev\";b:0;}i:3;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^3.6.4\";s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:LIVEWIRE\";s:14:\"\0*\0packageName\";s:17:\"livewire/livewire\";s:10:\"\0*\0version\";s:6:\"3.7.15\";s:6:\"\0*\0dev\";b:0;}i:4;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^1.7.0\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:VOLT\";s:14:\"\0*\0packageName\";s:13:\"livewire/volt\";s:10:\"\0*\0version\";s:6:\"1.10.5\";s:6:\"\0*\0dev\";b:0;}i:5;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:1:\"*\";s:10:\"\0*\0package\";E:36:\"Laravel\\Roster\\Enums\\Packages:BREEZE\";s:14:\"\0*\0packageName\";s:14:\"laravel/breeze\";s:10:\"\0*\0version\";s:5:\"2.4.1\";s:6:\"\0*\0dev\";b:1;}i:6;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.5.9\";s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.5.9\";s:6:\"\0*\0dev\";b:1;}i:7;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.29.0\";s:6:\"\0*\0dev\";b:1;}i:8;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.53.0\";s:6:\"\0*\0dev\";b:1;}i:9;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^3.8\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PEST\";s:14:\"\0*\0packageName\";s:12:\"pestphp/pest\";s:10:\"\0*\0version\";s:5:\"3.8.6\";s:6:\"\0*\0dev\";b:1;}i:10;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"11.5.50\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:7:\"11.5.50\";s:6:\"\0*\0dev\";b:1;}i:11;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:ALPINEJS\";s:14:\"\0*\0packageName\";s:8:\"alpinejs\";s:10:\"\0*\0version\";s:7:\"3.15.11\";s:6:\"\0*\0dev\";b:0;}i:12;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:6:\"3.4.19\";s:6:\"\0*\0dev\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1776747649;}', 1776834049);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_locks_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for cashier_shifts
-- ----------------------------
DROP TABLE IF EXISTS `cashier_shifts`;
CREATE TABLE `cashier_shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NULL DEFAULT NULL,
  `starting_cash` bigint NOT NULL DEFAULT 0 COMMENT 'Modal uang kembalian awal',
  `expected_cash` bigint NOT NULL DEFAULT 0 COMMENT 'Total uang yang seharusnya ada di laci',
  `actual_cash` bigint NOT NULL DEFAULT 0 COMMENT 'Total uang fisik yang dihitung kasir saat tutup',
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cashier_shifts_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `cashier_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cashier_shifts
-- ----------------------------
INSERT INTO `cashier_shifts` VALUES (1, 3, '2026-04-20 10:47:15', '2026-04-20 11:56:32', 500000, 545000, 60000, 'closed', '2026-04-20 10:47:15', '2026-04-20 11:56:32');
INSERT INTO `cashier_shifts` VALUES (2, 3, '2026-04-20 11:58:57', '2026-04-20 12:18:15', 500000, 500000, 500000, 'closed', '2026-04-20 11:58:57', '2026-04-20 12:18:15');
INSERT INTO `cashier_shifts` VALUES (3, 3, '2026-04-20 19:30:59', NULL, 500000, 365000, 0, 'open', '2026-04-20 19:30:59', '2026-04-20 22:18:19');
INSERT INTO `cashier_shifts` VALUES (4, 2, '2026-04-21 15:43:59', '2026-04-21 20:33:41', 500000, 542000, 600000, 'closed', '2026-04-21 15:43:59', '2026-04-21 20:33:41');
INSERT INTO `cashier_shifts` VALUES (5, 2, '2026-04-21 22:02:45', NULL, 0, 52000, 0, 'open', '2026-04-21 22:02:45', '2026-04-21 22:24:21');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, 'Obat Bebas', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `categories` VALUES (2, 'Obat Bebas Terbatas', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `categories` VALUES (3, 'Obat Keras', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `categories` VALUES (4, 'Vitamin & Suplemen', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `categories` VALUES (5, 'Alat Kesehatan', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `categories` VALUES (6, 'efeawfef314r3 fr', '2026-04-19 13:37:41', '2026-04-19 13:37:47');
INSERT INTO `categories` VALUES (7, 'afdafafdsfad', '2026-04-19 14:15:06', '2026-04-19 14:15:12');

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (1, 'SOFYAN YUNUS ROHMAN', '0895341935848', 'Jl. kunci desa sudagaran kec. Sidareja, Cilacap, Jawa tengah', '2026-04-19 00:10:40', '2026-04-19 00:10:40');
INSERT INTO `customers` VALUES (2, 'SOFYAN YUNUS ROHMAN', '0895341935848', 'Jl. kunci desa sudagaran kec. Sidareja, Cilacap, Jawa tengah', '2026-04-19 13:29:32', '2026-04-19 13:29:32');

-- ----------------------------
-- Table structure for held_transactions
-- ----------------------------
DROP TABLE IF EXISTS `held_transactions`;
CREATE TABLE `held_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cart_data` json NOT NULL COMMENT 'Menyimpan array cart Livewire',
  `total_price` bigint NOT NULL DEFAULT 0,
  `reference_notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Misal: Bapak Baju Merah',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `held_transactions_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `held_transactions_customer_id_foreign`(`customer_id` ASC) USING BTREE,
  CONSTRAINT `held_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `held_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of held_transactions
-- ----------------------------
INSERT INTO `held_transactions` VALUES (5, 2, NULL, '[{\"name\": \"Amoxicillin 500mg\", \"quantity\": 1, \"subtotal\": 12000, \"product_id\": 2, \"unit_price\": 12000}]', 0, 'test 2', '2026-04-21 18:13:34', '2026-04-21 18:13:34');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '2026_03_17_211105_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_03_29_100507_create_customers_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_03_30_100509_create_sales_table', 1);
INSERT INTO `migrations` VALUES (6, '2026_04_07_044502_create_suppliers_table', 1);
INSERT INTO `migrations` VALUES (7, '2026_04_07_071855_create_audit_logs_table', 1);
INSERT INTO `migrations` VALUES (8, '2026_04_08_044010_create_purchases_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_04_11_085111_create_categories_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_04_11_085112_create_units_table', 1);
INSERT INTO `migrations` VALUES (11, '2026_04_11_135326_create_products_table', 1);
INSERT INTO `migrations` VALUES (12, '2026_04_11_185114_create_product_batches_table', 1);
INSERT INTO `migrations` VALUES (13, '2026_04_11_250023_create_purchase_items_table', 1);
INSERT INTO `migrations` VALUES (14, '2026_04_11_275441_create_sale_items_table', 1);
INSERT INTO `migrations` VALUES (15, '2026_04_15_215713_create_purchase_orders_table', 1);
INSERT INTO `migrations` VALUES (16, '2026_04_15_220118_create_purchase_order_items_table', 1);
INSERT INTO `migrations` VALUES (17, '2026_04_18_130630_create_stock_adjustments_table', 1);
INSERT INTO `migrations` VALUES (18, '2026_04_18_144641_create_purchase_returns_table', 1);
INSERT INTO `migrations` VALUES (19, '2026_04_18_150708_create_purchase_return_items_table', 1);
INSERT INTO `migrations` VALUES (20, '2026_04_19_004512_create_pharmacy_profiles_table', 2);
INSERT INTO `migrations` VALUES (21, '2026_04_20_093810_add_enterprise_pos_features', 3);
INSERT INTO `migrations` VALUES (22, '2026_04_21_084252_add_payment_method_to_sales_table', 4);
INSERT INTO `migrations` VALUES (23, '2026_04_21_120504_add_qris_string_to_pharmacy_profiles_table', 5);

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE,
  INDEX `personal_access_tokens_expires_at_index`(`expires_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for pharmacy_profiles
-- ----------------------------
DROP TABLE IF EXISTS `pharmacy_profiles`;
CREATE TABLE `pharmacy_profiles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Apotek Default',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `qris_string` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `apoteker_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sipa_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pharmacy_profiles
-- ----------------------------
INSERT INTO `pharmacy_profiles` VALUES (1, 'Berkah Jaya', 'afsafwegw', '00020101021126570011ID.DANA.WWW011893600915300093781102090009378110303UMI51440014ID.CO.QRIS.WWW0215ID10264899231630303UMI5204549953033605802ID5912BUKBER JKBLC6015Kabupaten Cilac61055321163041D75', '42545', 'Mampus S.Farm', 'wfa4t24g34w', 'logos/eRtVGwMV0zJ4ixfJPtk4DeEXdm0oGzgVqCmFBfj1.png', '2026-04-19 12:20:14', '2026-04-21 15:18:40');

-- ----------------------------
-- Table structure for product_batches
-- ----------------------------
DROP TABLE IF EXISTS `product_batches`;
CREATE TABLE `product_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_date` date NOT NULL,
  `purchase_price` bigint NOT NULL,
  `stock` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_batches_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_batches
-- ----------------------------
INSERT INTO `product_batches` VALUES (1, 1, 'BATCH-A-3469', '2026-10-18', 3500, 6, '2026-04-18 21:14:01', '2026-04-21 22:20:43');
INSERT INTO `product_batches` VALUES (2, 1, 'BATCH-B-6098', '2027-04-18', 3750, 102, '2026-04-18 21:14:01', '2026-04-21 18:08:05');
INSERT INTO `product_batches` VALUES (3, 2, 'BATCH-A-8248', '2026-10-18', 8400, 33, '2026-04-18 21:14:01', '2026-04-21 22:24:21');
INSERT INTO `product_batches` VALUES (4, 2, 'BATCH-B-4893', '2027-04-18', 9000, 86, '2026-04-18 21:14:01', '2026-04-21 18:08:05');
INSERT INTO `product_batches` VALUES (5, 3, 'BATCH-A-8993', '2026-10-18', 31500, 25, '2026-04-18 21:14:01', '2026-04-20 11:06:16');
INSERT INTO `product_batches` VALUES (6, 3, 'BATCH-B-1876', '2027-04-18', 33750, 103, '2026-04-18 21:14:01', '2026-04-20 22:18:19');
INSERT INTO `product_batches` VALUES (7, 1, '4234', '2026-05-09', 10221, 0, '2026-04-19 14:35:21', '2026-04-19 15:06:21');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `selling_price` bigint NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `products_sku_unique`(`sku` ASC) USING BTREE,
  INDEX `products_category_id_foreign`(`category_id` ASC) USING BTREE,
  INDEX `products_unit_id_foreign`(`unit_id` ASC) USING BTREE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 1, 1, '899123456001', 'Paracetamol 500mg', 5000, 1, '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `products` VALUES (2, 3, 1, '899123456002', 'Amoxicillin 500mg', 12000, 1, '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `products` VALUES (3, 4, 2, '899123456003', 'Imboost Force Sirup 60ml', 45000, 1, '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `products` VALUES (4, 2, 2, 'fawefa', 'vsdaavrgw', 35321513, 1, '2026-04-19 13:36:18', '2026-04-19 13:36:18');
INSERT INTO `products` VALUES (5, 3, 3, 'afafasfasd', 'cscasdfe', 2000000, 1, '2026-04-21 07:23:33', '2026-04-21 07:47:20');

-- ----------------------------
-- Table structure for purchase_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` bigint NOT NULL,
  `subtotal` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_items_purchase_id_foreign`(`purchase_id` ASC) USING BTREE,
  INDEX `purchase_items_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_items
-- ----------------------------
INSERT INTO `purchase_items` VALUES (1, 1, 1, 1, 10222, 10222, '2026-04-19 14:35:21', '2026-04-19 14:35:21');

-- ----------------------------
-- Table structure for purchase_order_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` int NOT NULL,
  `subtotal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_order_items_purchase_order_id_foreign`(`purchase_order_id` ASC) USING BTREE,
  INDEX `purchase_order_items_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_order_items
-- ----------------------------
INSERT INTO `purchase_order_items` VALUES (1, 1, 1, 1, 11110, 11110, '2026-04-19 14:36:15', '2026-04-19 14:36:15');

-- ----------------------------
-- Table structure for purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `expected_date` date NULL DEFAULT NULL,
  `status` enum('pending','received','cancelled','ordered') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` int NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchase_orders_po_number_unique`(`po_number` ASC) USING BTREE,
  INDEX `purchase_orders_supplier_id_foreign`(`supplier_id` ASC) USING BTREE,
  INDEX `purchase_orders_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------
INSERT INTO `purchase_orders` VALUES (1, 'PO-20260419143615', 2, 2, '2026-04-19', '2026-05-07', 'pending', 11110, '', '2026-04-19 14:36:15', '2026-04-19 14:36:15');

-- ----------------------------
-- Table structure for purchase_return_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_return_items`;
CREATE TABLE `purchase_return_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_return_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_batch_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(15, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_return_items_purchase_return_id_foreign`(`purchase_return_id` ASC) USING BTREE,
  INDEX `purchase_return_items_product_id_foreign`(`product_id` ASC) USING BTREE,
  INDEX `purchase_return_items_product_batch_id_foreign`(`product_batch_id` ASC) USING BTREE,
  CONSTRAINT `purchase_return_items_product_batch_id_foreign` FOREIGN KEY (`product_batch_id`) REFERENCES `product_batches` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `purchase_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `purchase_return_items_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_return_items
-- ----------------------------
INSERT INTO `purchase_return_items` VALUES (1, 1, 2, 4, 10, 'Rusak / Cacat Fisik', 9000.00, '2026-04-19 00:09:47', '2026-04-19 00:09:47');
INSERT INTO `purchase_return_items` VALUES (2, 2, 2, 3, 5, 'Recall Pabrik / BPOM', 8400.00, '2026-04-19 13:27:10', '2026-04-19 13:27:10');

-- ----------------------------
-- Table structure for purchase_returns
-- ----------------------------
DROP TABLE IF EXISTS `purchase_returns`;
CREATE TABLE `purchase_returns`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total_return_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchase_returns_return_number_unique`(`return_number` ASC) USING BTREE,
  INDEX `purchase_returns_supplier_id_foreign`(`supplier_id` ASC) USING BTREE,
  INDEX `purchase_returns_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `purchase_returns_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_returns
-- ----------------------------
INSERT INTO `purchase_returns` VALUES (1, 'RTV-260419-424', 1, 2, '2026-04-19', '', 90000.00, '2026-04-19 00:09:47', '2026-04-19 00:09:47');
INSERT INTO `purchase_returns` VALUES (2, 'RTV-260419-459', 2, 2, '2026-04-19', '', 42000.00, '2026-04-19 13:27:10', '2026-04-19 13:27:10');

-- ----------------------------
-- Table structure for purchases
-- ----------------------------
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `purchase_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `total_cost` bigint NOT NULL DEFAULT 0,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchases_purchase_number_unique`(`purchase_number` ASC) USING BTREE,
  INDEX `purchases_supplier_id_foreign`(`supplier_id` ASC) USING BTREE,
  INDEX `purchases_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchases
-- ----------------------------
INSERT INTO `purchases` VALUES (1, 3, 'PRC-20260419143521', '2026-04-19', 10222, 2, '2026-04-19 14:35:21', '2026-04-19 14:35:21');

-- ----------------------------
-- Table structure for sale_items
-- ----------------------------
DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` bigint NOT NULL,
  `subtotal` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sale_items_sale_id_foreign`(`sale_id` ASC) USING BTREE,
  INDEX `sale_items_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sale_items
-- ----------------------------
INSERT INTO `sale_items` VALUES (1, 1, 1, 3, 5000, 15000, '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `sale_items` VALUES (2, 2, 1, 11, 5000, 55000, '2026-04-19 23:48:02', '2026-04-19 23:48:02');
INSERT INTO `sale_items` VALUES (3, 3, 3, 8, 45000, 360000, '2026-04-20 06:50:08', '2026-04-20 06:50:08');
INSERT INTO `sale_items` VALUES (4, 4, 2, 3, 12000, 36000, '2026-04-20 06:53:58', '2026-04-20 06:53:58');
INSERT INTO `sale_items` VALUES (5, 5, 3, 4, 45000, 180000, '2026-04-20 07:06:32', '2026-04-20 07:06:32');
INSERT INTO `sale_items` VALUES (6, 6, 3, 12, 45000, 540000, '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `sale_items` VALUES (7, 6, 2, 6, 12000, 72000, '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `sale_items` VALUES (8, 6, 1, 6, 5000, 30000, '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `sale_items` VALUES (9, 7, 3, 1, 45000, 45000, '2026-04-20 11:06:16', '2026-04-20 11:06:16');
INSERT INTO `sale_items` VALUES (10, 8, 2, 1, 12000, 12000, '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `sale_items` VALUES (11, 8, 1, 2, 5000, 10000, '2026-04-21 08:51:13', '2026-04-21 08:51:13');
INSERT INTO `sale_items` VALUES (12, 9, 1, 1, 5000, 5000, '2026-04-21 12:49:36', '2026-04-21 12:49:36');
INSERT INTO `sale_items` VALUES (13, 10, 1, 1, 5000, 5000, '2026-04-21 15:46:13', '2026-04-21 15:46:13');
INSERT INTO `sale_items` VALUES (14, 11, 1, 7, 5000, 35000, '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `sale_items` VALUES (15, 11, 2, 4, 12000, 48000, '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `sale_items` VALUES (16, 13, 1, 5, 5000, 25000, '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `sale_items` VALUES (17, 13, 2, 1, 12000, 12000, '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `sale_items` VALUES (18, 14, 1, 1, 5000, 5000, '2026-04-21 18:40:25', '2026-04-21 18:40:25');
INSERT INTO `sale_items` VALUES (19, 15, 1, 8, 5000, 40000, '2026-04-21 22:20:43', '2026-04-21 22:20:43');
INSERT INTO `sale_items` VALUES (20, 16, 2, 1, 12000, 12000, '2026-04-21 22:23:54', '2026-04-21 22:23:54');
INSERT INTO `sale_items` VALUES (21, 17, 2, 1, 12000, 12000, '2026-04-21 22:24:21', '2026-04-21 22:24:21');

-- ----------------------------
-- Table structure for sales
-- ----------------------------
DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_price` bigint NOT NULL DEFAULT 0,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pembayaran` bigint NOT NULL DEFAULT 0,
  `kembalian` bigint NOT NULL DEFAULT 0,
  `status` enum('completed','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `void_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sales_invoice_number_unique`(`invoice_number` ASC) USING BTREE,
  INDEX `sales_customer_id_foreign`(`customer_id` ASC) USING BTREE,
  INDEX `sales_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sales
-- ----------------------------
INSERT INTO `sales` VALUES (1, 'INV-260419150621', NULL, 2, 15000, 'cash', NULL, 20000, 5000, 'completed', NULL, '2026-04-19 15:06:21', '2026-04-19 15:06:21');
INSERT INTO `sales` VALUES (2, 'INV-260419234802', NULL, 2, 55000, 'cash', NULL, 2343525, 2288525, 'completed', NULL, '2026-04-19 23:48:02', '2026-04-19 23:48:02');
INSERT INTO `sales` VALUES (3, 'INV-260420065008', NULL, 2, 360000, 'cash', NULL, 380000, 20000, 'completed', NULL, '2026-04-20 06:50:08', '2026-04-20 06:50:08');
INSERT INTO `sales` VALUES (4, 'INV-260420065358', NULL, 2, 36000, 'cash', NULL, 40000, 4000, 'completed', NULL, '2026-04-20 06:53:58', '2026-04-20 06:53:58');
INSERT INTO `sales` VALUES (5, 'INV-260420070632', NULL, 2, 180000, 'cash', NULL, 200000, 20000, 'completed', NULL, '2026-04-20 07:06:32', '2026-04-20 07:06:32');
INSERT INTO `sales` VALUES (6, 'INV-260420103824', NULL, 2, 642000, 'cash', NULL, 700000, 58000, 'completed', NULL, '2026-04-20 10:38:24', '2026-04-20 10:38:24');
INSERT INTO `sales` VALUES (7, 'INV-260420110616', NULL, 3, 45000, 'cash', NULL, 50000, 5000, 'void', 'tes void (Oleh: Apoteker Sofyan)', '2026-04-20 11:06:16', '2026-04-20 22:18:19');
INSERT INTO `sales` VALUES (8, 'INV-260421085113', NULL, 2, 22000, 'qris', '79685896', 22000, 0, 'void', 'test void (Oleh: Apoteker Sofyan)', '2026-04-21 08:51:13', '2026-04-21 18:08:05');
INSERT INTO `sales` VALUES (9, 'INV-260421124936', NULL, 2, 5000, 'qris', 'QRIS-20260421124936', 5000, 0, 'completed', NULL, '2026-04-21 12:49:36', '2026-04-21 12:49:36');
INSERT INTO `sales` VALUES (10, 'INV-260421154613', NULL, 2, 5000, 'qris', 'QR69E739557AA3C', 5000, 0, 'completed', NULL, '2026-04-21 15:46:13', '2026-04-21 15:46:13');
INSERT INTO `sales` VALUES (11, 'INV-260421180404', NULL, 2, 83000, 'qris', 'QR69E759A43343F', 83000, 0, 'completed', NULL, '2026-04-21 18:04:04', '2026-04-21 18:04:04');
INSERT INTO `sales` VALUES (13, 'INV-260421183016', NULL, 2, 37000, 'cash', NULL, 40000, 3000, 'completed', NULL, '2026-04-21 18:30:16', '2026-04-21 18:30:16');
INSERT INTO `sales` VALUES (14, 'INV-260421184025', NULL, 2, 5000, 'cash', NULL, 6000, 1000, 'completed', NULL, '2026-04-21 18:40:25', '2026-04-21 18:40:25');
INSERT INTO `sales` VALUES (15, 'INV-260421222043', NULL, 2, 40000, 'cash', NULL, 333333, 293333, 'completed', NULL, '2026-04-21 22:20:43', '2026-04-21 22:20:43');
INSERT INTO `sales` VALUES (16, 'INV-260421222354', NULL, 2, 12000, 'qris', 'QR69E7968A204B1', 12000, 0, 'completed', NULL, '2026-04-21 22:23:54', '2026-04-21 22:23:54');
INSERT INTO `sales` VALUES (17, 'INV-260421222421', NULL, 2, 12000, 'cash', NULL, 15000, 3000, 'completed', NULL, '2026-04-21 22:24:21', '2026-04-21 22:24:21');

-- ----------------------------
-- Table structure for stock_adjustments
-- ----------------------------
DROP TABLE IF EXISTS `stock_adjustments`;
CREATE TABLE `stock_adjustments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_batch_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `system_qty` int NOT NULL,
  `physical_qty` int NOT NULL,
  `difference` int NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stock_adjustments_product_id_foreign`(`product_id` ASC) USING BTREE,
  INDEX `stock_adjustments_product_batch_id_foreign`(`product_batch_id` ASC) USING BTREE,
  INDEX `stock_adjustments_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `stock_adjustments_product_batch_id_foreign` FOREIGN KEY (`product_batch_id`) REFERENCES `product_batches` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adjustments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stock_adjustments
-- ----------------------------
INSERT INTO `stock_adjustments` VALUES (1, 2, 4, 2, 90, 85, -5, 'Barang Hilang', '2026-04-19 00:10:24', '2026-04-19 00:10:24');
INSERT INTO `stock_adjustments` VALUES (2, 2, 3, 2, 45, 50, 5, 'Koreksi Salah Input', '2026-04-19 13:28:44', '2026-04-19 13:28:44');

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES (1, 'akwafkw', 'cilacap', '0888024242', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `suppliers` VALUES (2, 'adacv', 'ASVESDEFQ\n', '4125', '2026-04-19 11:44:45', '2026-04-19 11:44:45');
INSERT INTO `suppliers` VALUES (3, 'apoopopop', 'fwefailwehfaewbvawebuawueb\n', '5294182598294213512512', '2026-04-19 13:35:45', '2026-04-19 13:35:52');

-- ----------------------------
-- Table structure for units
-- ----------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of units
-- ----------------------------
INSERT INTO `units` VALUES (1, 'Strip', 'str', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `units` VALUES (2, 'Botol', 'btl', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `units` VALUES (3, 'Tablet', 'tab', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `units` VALUES (4, 'Kapsul', 'kps', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `units` VALUES (5, 'Tube', 'tb', '2026-04-18 21:14:01', '2026-04-18 21:14:01');
INSERT INTO `units` VALUES (6, 'Box', 'box', '2026-04-18 21:14:01', '2026-04-18 21:14:01');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'PIN untuk Fast Login Kasir',
  `role` enum('owner','admin','kasir') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Bapak Owner', 'owner@apotek.com', NULL, '$2y$12$svANzKMLvdLu/dsU6KPGnuJ3Lg3RJH5.N0DYcJsuODOEw7IJAyrDe', '111111', 'owner', '6N8czdSCMH9P49ju994zUitT97XosIy9gvnkJFNPzC28akriPhe3R6dVaqnr', '2026-04-18 19:03:42', '2026-04-18 19:03:42');
INSERT INTO `users` VALUES (2, 'Apoteker Sofyan', 'admin@apotek.com', NULL, '$2y$12$vXei.pB7ouBgkh.sCf4Uo.wD7SBHX3LcGLYa22Axj7zcYuVCCqgpa', '222222', 'admin', NULL, '2026-04-18 19:03:43', '2026-04-21 21:35:28');
INSERT INTO `users` VALUES (3, 'Kasir Depan', 'kasir@apotek.com', NULL, '$2y$12$QkLQanqMjhrHvTSqhrxJUekewjJWY8AVhnG1sqzr3a.cmVirQhVQu', '333333', 'kasir', NULL, '2026-04-18 19:03:45', '2026-04-18 19:03:45');

SET FOREIGN_KEY_CHECKS = 1;
