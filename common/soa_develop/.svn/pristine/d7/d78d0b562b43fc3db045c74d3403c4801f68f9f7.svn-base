/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-08-30 17:23:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `shopping`
-- ----------------------------
DROP TABLE IF EXISTS `shopping`;
CREATE TABLE `shopping` (
  `shopping_id` int(10) NOT NULL AUTO_INCREMENT,
  `source_number` varchar(30) DEFAULT NULL COMMENT '资源编号',
  `supplier_id` int(10) DEFAULT NULL COMMENT '供应商ID',
  `supplier_type` tinyint(4) DEFAULT NULL COMMENT '1购物店2地接社',
  `company_id` int(11) DEFAULT NULL COMMENT '属于哪个分公司ID',
  `shopping_name` varchar(900) NOT NULL COMMENT '购物店名字',
  `shopping_type` tinyint(4) DEFAULT '1' COMMENT '类型1 有全陪 2 无全陪 3 小车司机参赌 4 小车司机不参赌',
  `remark` text COMMENT '备注',
  `default_language_id` int(11) DEFAULT NULL COMMENT '默认语言ID',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人ID',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '更新人ID',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态1启用2禁用',
  PRIMARY KEY (`shopping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='导游表';

-- ----------------------------
-- Records of shopping
-- ----------------------------
