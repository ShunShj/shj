/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-04-15 16:43:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company_order_product_template`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_template`;
CREATE TABLE `company_order_product_template` (
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '公司订单',
  `branch_product_number` varchar(30) DEFAULT NULL COMMENT '分公司产品编号',
  `company_order_product_id` int(10) DEFAULT NULL COMMENT '公司订单分公司产品ID',
  `route_template_number` varchar(30) DEFAULT NULL COMMENT '线路模板编号',
  `team_product_id` varchar(30) DEFAULT NULL COMMENT '团队产品ID',
  `company_order_product_team_id` int(10) DEFAULT NULL COMMENT '公司订单产品团队ID',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建用户ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of company_order_product_template
-- ----------------------------
