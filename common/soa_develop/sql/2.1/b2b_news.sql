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

 Date: 15/11/2019 17:26:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for b2b_news
-- ----------------------------
DROP TABLE IF EXISTS `b2b_news`;
CREATE TABLE `b2b_news`  (
  `news_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '标题',
  `cn_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '中文标题',
  `sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '副标题',
  `date` date DEFAULT NULL COMMENT '日期',
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '多图',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '内容',
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`news_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
