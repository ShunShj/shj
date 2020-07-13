/*
Navicat MySQL Data Transfer

Source Server         : 192.168.11.122
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-03-28 09:40:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for route_once_price
-- ----------------------------
DROP TABLE IF EXISTS `route_once_price`;
CREATE TABLE `route_once_price` (
  `route_once_price_id` int(10) NOT NULL AUTO_INCREMENT,
  `route_template_id` int(10) DEFAULT NULL COMMENT '团队项目ID',
  `company_id` int(11) DEFAULT NULL COMMENT '可见分公司',
  `total_price` decimal(10,2) DEFAULT NULL,
  `once_price_type` tinyint(4) DEFAULT NULL COMMENT '1团费2自费',
  `team_price_currency_id` int(10) DEFAULT NULL COMMENT '团费货币 ID',
  `own_price_currency_id` int(11) DEFAULT NULL COMMENT '自费货币ID',
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `status` int(10) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`route_once_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
