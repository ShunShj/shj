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

 Date: 17/07/2019 13:19:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_website
-- ----------------------------
DROP TABLE IF EXISTS `ota_website`;
CREATE TABLE `ota_website`  (
  `ota_website_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '图片地址',
  `web_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '官网名称',
  `web_href` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '官网连接',
  `time_zone_id` tinyint(4) DEFAULT NULL COMMENT '时区ID',
  `company_id` int(10) DEFAULT NULL COMMENT '所属分公司',
  `language_id` int(10) DEFAULT NULL COMMENT '默认语言ID',
  `currency_id` int(10) DEFAULT NULL COMMENT '默认币种ID',
  `put_on_records` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备案信息',
  `tourism_business_license_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '旅游经营许可证号',
  `reservation_phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '预定热线',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '邮箱',
  `scope_of_business` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '经营范围',
  `company_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '公司地址',
  `about_us` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '关于我们',
  `contact_us` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '联系我们',
  `create_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `status` tinyint(4) UNSIGNED DEFAULT 1 COMMENT '1启用0禁用',
  PRIMARY KEY (`ota_website_id`) USING BTREE,
  UNIQUE INDEX `uuid_only`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
