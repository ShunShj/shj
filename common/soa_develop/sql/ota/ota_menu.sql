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

 Date: 17/07/2019 13:19:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ota_menu
-- ----------------------------
DROP TABLE IF EXISTS `ota_menu`;
CREATE TABLE `ota_menu`  (
  `ota_menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ota_menu_list_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '列表uuid',
  `website_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '1' COMMENT '网站',
  `company_id` int(11) UNSIGNED DEFAULT 1 COMMENT '公司',
  `menu_name` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '菜单标题',
  `pid` int(10) DEFAULT 0 COMMENT '父级关系1级为0',
  `sorting` int(11) DEFAULT NULL COMMENT '排序号',
  `style` int(11) UNSIGNED DEFAULT 1 COMMENT '样式',
  `href_type` tinyint(4) DEFAULT NULL COMMENT '1内部连接2外部连接',
  `without_href` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '外部跳转URL',
  `interior_type` tinyint(4) DEFAULT NULL COMMENT '内部跳转类型1旅游产品分类2旅游产品3文章分类4文章',
  `interior_uuid` varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '内部跳转的UUID ',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`ota_menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
