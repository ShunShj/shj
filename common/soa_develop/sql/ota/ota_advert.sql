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

 Date: 17/07/2019 13:17:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_advert
-- ----------------------------
DROP TABLE IF EXISTS `ota_advert`;
CREATE TABLE `ota_advert`  (
  `ota_advert_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '广告表id',
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '1' COMMENT '所属网站',
  `ota_advert_list_uuid` varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '1' COMMENT '所属列表',
  `company_id` int(11) UNSIGNED DEFAULT 0 COMMENT '所属分公司',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '标题',
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '图片资源',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '链接地址',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '描述',
  `sort` int(11) UNSIGNED DEFAULT 0 COMMENT '排序',
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `update_user_id` int(11) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1显示0不显示',
  PRIMARY KEY (`ota_advert_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
