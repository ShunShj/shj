/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-07-19 10:05:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `source_type`
-- ----------------------------
DROP TABLE IF EXISTS `source_type`;
CREATE TABLE `source_type` (
  `supplier_type_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '资源类型',
  `supplier_type_name` varchar(30) DEFAULT NULL COMMENT '资源类型名称',
  `create_time` int(10) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用2禁用',
  `code_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`supplier_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of source_type
-- ----------------------------
INSERT INTO `source_type` VALUES ('1', '团队产品', '1532673330', '1', '1532673330', '1', '1', 'index_source_agent');
INSERT INTO `source_type` VALUES ('2', '酒店', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_hotel');
INSERT INTO `source_type` VALUES ('3', '用餐', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_dining');
INSERT INTO `source_type` VALUES ('4', '航班', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_flight');
INSERT INTO `source_type` VALUES ('5', '邮轮', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_cruise');
INSERT INTO `source_type` VALUES ('6', '签证', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_visa');
INSERT INTO `source_type` VALUES ('7', '景点', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_scenic_spot');
INSERT INTO `source_type` VALUES ('8', '车辆', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_vehicle');
INSERT INTO `source_type` VALUES ('9', '导游', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_tourguide');
INSERT INTO `source_type` VALUES ('10', '单项资源', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_singlesource');
INSERT INTO `source_type` VALUES ('11', '自费项目', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_ownexpense');
INSERT INTO `source_type` VALUES ('12', '购物店', '1532673330', '1', '1532673330', '1', '1', 'index_nav_shoppingShop');
INSERT INTO `source_type` VALUES ('13', '其他', '1532673330', '1', '1532673330', '1', '1', 'index_finance_showReceivableManage_other');
INSERT INTO `source_type` VALUES ('14', '团费项目', '1532673330', '1', '1532673330', '1', '0', null);
