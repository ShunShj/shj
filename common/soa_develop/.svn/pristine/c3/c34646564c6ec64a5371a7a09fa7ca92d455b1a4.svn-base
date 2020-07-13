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

 Date: 17/07/2019 13:18:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_advert_list
-- ----------------------------
DROP TABLE IF EXISTS `ota_advert_list`;
CREATE TABLE `ota_advert_list`  (
  `ota_advert_list_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0',
  `ota_advert_list_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '列表名称',
  `style` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '样式',
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT NULL,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `update_user_id` int(11) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`ota_advert_list_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
