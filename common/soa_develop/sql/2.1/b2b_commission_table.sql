/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.11.122
 Source Server Type    : MySQL
 Source Server Version : 100109
 Source Host           : 192.168.11.122:3306
 Source Schema         : erp

 Target Server Type    : MySQL
 Target Server Version : 100109
 File Encoding         : 65001

 Date: 12/11/2019 16:42:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for b2b_commission_table
-- ----------------------------
DROP TABLE IF EXISTS `b2b_commission_table`;
CREATE TABLE `b2b_commission_table`  (
  `commission_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '名称',
  `order` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '颜色',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '内容',
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`commission_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
