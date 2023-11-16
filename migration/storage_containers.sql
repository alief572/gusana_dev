/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80033 (8.0.33-0ubuntu0.22.04.2)
 Source Host           : localhost:3306
 Source Schema         : csj_db

 Target Server Type    : MySQL
 Target Server Version : 80033 (8.0.33-0ubuntu0.22.04.2)
 File Encoding         : 65001

 Date: 28/06/2023 06:20:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for storage_containers
-- ----------------------------
DROP TABLE IF EXISTS `storage_containers`;
CREATE TABLE `storage_containers` (
  `id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `storage_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `container_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `container_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cost_value` int DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of storage_containers
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
