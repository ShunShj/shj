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

 Date: 17/07/2019 13:18:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_article
-- ----------------------------
DROP TABLE IF EXISTS `ota_article`;
CREATE TABLE `ota_article`  (
  `ota_article_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '标题',
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `company_id` int(11) UNSIGNED DEFAULT 0 COMMENT '所属分公司',
  `ota_article_type_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '所属分类可多选',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '内容',
  `style` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '样式',
  `start_time` int(11) DEFAULT NULL COMMENT '上架时间',
  `end_time` int(11) DEFAULT NULL COMMENT '下架时间',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '描述信息',
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`ota_article_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
