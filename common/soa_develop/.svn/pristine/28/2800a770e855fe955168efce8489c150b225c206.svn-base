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

 Date: 17/07/2019 13:19:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_slide
-- ----------------------------
DROP TABLE IF EXISTS `ota_slide`;
CREATE TABLE `ota_slide`  (
  `ota_slide_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '1' COMMENT '所属网站',
  `ota_slide_list_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '所属列表uuid',
  `pic` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '图片地址',
  `company_id` int(11) UNSIGNED DEFAULT 0 COMMENT '所属分公司',
  `type` int(11) DEFAULT 1 COMMENT '类型',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '广告标题',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '链接',
  `sort` int(11) UNSIGNED DEFAULT 0 COMMENT '排序',
  `create_time` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1显示0不显示',
  PRIMARY KEY (`ota_slide_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
