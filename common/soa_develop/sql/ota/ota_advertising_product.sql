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

 Date: 17/07/2019 13:18:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_advertising_product
-- ----------------------------
DROP TABLE IF EXISTS `ota_advertising_product`;
CREATE TABLE `ota_advertising_product`  (
  `ota_advertising_product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `website_uuid` varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '1',
  `ota_advertising_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `company_id` int(11) UNSIGNED DEFAULT 1,
  `product_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `banner_image` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '广告图片地址',
  `currency_id` int(10) DEFAULT NULL,
  `price` decimal(10, 2) DEFAULT NULL,
  `tag_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '标签',
  `team_product_id` int(10) DEFAULT NULL,
  `sort` int(10) UNSIGNED DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  `update_time` int(11) UNSIGNED DEFAULT NULL,
  `create_user_id` int(11) UNSIGNED DEFAULT 1,
  `update_user_id` int(11) UNSIGNED DEFAULT 1,
  `create_time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`ota_advertising_product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;



ALTER TABLE `ota_advertising_product` 
ADD COLUMN `href_type` tinyint(4) UNSIGNED DEFAULT 0 COMMENT '1内部连接2外部连接' AFTER `tag_name`,
ADD COLUMN `without_href` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '外部跳转URL' AFTER `href_type`,
ADD COLUMN `interior_type` tinyint(4) COMMENT '内部跳转类型1旅游产品分类2旅游产品' AFTER `without_href`,
ADD COLUMN `interior_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '内部跳转的UUID' AFTER `interior_type`;


ALTER TABLE `ota_advertising_product` 
MODIFY COLUMN `ota_advertising_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' AFTER `website_uuid`,
MODIFY COLUMN `product_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' AFTER `company_id`,
MODIFY COLUMN `banner_image` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '广告图片地址' AFTER `product_title`,
MODIFY COLUMN `currency_id` int(10) UNSIGNED DEFAULT 0 AFTER `banner_image`,
MODIFY COLUMN `price` decimal(10, 2) UNSIGNED DEFAULT 0 AFTER `currency_id`,
MODIFY COLUMN `tag_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '标签' AFTER `price`,
MODIFY COLUMN `sort` int(10) UNSIGNED DEFAULT 0 COMMENT '排序' AFTER `team_product_uuid`;