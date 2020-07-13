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

 Date: 19/08/2019 16:46:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_menu_list
-- ----------------------------
DROP TABLE IF EXISTS `ota_menu_list`;
CREATE TABLE `ota_menu_list`  (
  `ota_menu_list_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '菜单标题',
  `type` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1头部菜单2尾部菜单3其他位置',
  `style` int(11) UNSIGNED DEFAULT 1 COMMENT '样式',
  `create_time` int(11) UNSIGNED DEFAULT 1,
  `update_time` int(11) UNSIGNED DEFAULT 1,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1启用0禁用',
  PRIMARY KEY (`ota_menu_list_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
