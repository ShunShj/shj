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

 Date: 11/11/2019 19:48:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for b2b_tour_type
-- ----------------------------
DROP TABLE IF EXISTS `b2b_tour_type`;
CREATE TABLE `b2b_tour_type`  (
  `tour_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tour_type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '分类名称',
  `cn_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '中文名称',
  `pid` int(11) UNSIGNED DEFAULT 0 COMMENT '父级id',
  `company_id` int(10) UNSIGNED DEFAULT 1,
  `system_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '系统类型',
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` int(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`tour_type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
