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

 Date: 17/07/2019 13:18:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_article_type
-- ----------------------------
DROP TABLE IF EXISTS `ota_article_type`;
CREATE TABLE `ota_article_type`  (
  `ota_article_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '所属网站uuid',
  `company_id` int(11) UNSIGNED DEFAULT 1 COMMENT '所属分公司',
  `article_type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '分类名称',
  `level` int(4) UNSIGNED DEFAULT 1 COMMENT '分类等级',
  `pid` int(11) UNSIGNED DEFAULT 0 COMMENT '所属上级',
  `create_time` int(11) UNSIGNED DEFAULT 1,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_time` int(11) UNSIGNED DEFAULT 1,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`ota_article_type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
