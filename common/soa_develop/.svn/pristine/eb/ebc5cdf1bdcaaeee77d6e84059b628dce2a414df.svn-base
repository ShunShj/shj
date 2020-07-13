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

 Date: 17/07/2019 13:18:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_advertising_subtitle
-- ----------------------------
DROP TABLE IF EXISTS `ota_advertising_subtitle`;
CREATE TABLE `ota_advertising_subtitle`  (
  `ota_advertising_subtitle_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ota_advertising_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `subtitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '副标题',
  `href_type` tinyint(4) DEFAULT NULL COMMENT '1内部连接2外部连接',
  `without_href` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '外部跳转URL',
  `interior_type` tinyint(4) DEFAULT NULL COMMENT '内部跳转类型1旅游产品分类2旅游产品',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '链接',
  `interior_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '内部跳转的UUID ',
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1启动0禁用',
  PRIMARY KEY (`ota_advertising_subtitle_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
