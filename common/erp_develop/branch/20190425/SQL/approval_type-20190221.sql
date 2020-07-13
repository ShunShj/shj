/*
Navicat MySQL Data Transfer

Source Server         : 47.244.53.248 erp测试服
Source Server Version : 50724
Source Host           : 47.244.53.248:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2019-02-21 09:57:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `approval_type`
-- ----------------------------
DROP TABLE IF EXISTS `approval_type`;
CREATE TABLE `approval_type` (
  `approval_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `apellation` varchar(80) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '对应 examine_and_approve_id 子集',
  `level` tinyint(2) DEFAULT '1' COMMENT '级别 分3级目前',
  `status` tinyint(2) DEFAULT '1' COMMENT '1.开启 2.关闭',
  PRIMARY KEY (`approval_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='审批类型  ';

-- ----------------------------
-- Records of approval_type
-- ----------------------------
INSERT INTO `approval_type` VALUES ('1', '业务审批', '0', '1', '1');
INSERT INTO `approval_type` VALUES ('2', '创建供应商', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('3', '变更供应商', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('4', '创建导游', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('5', '变更导游', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('6', '创建地接', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('7', '变更地接', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('8', '创建单项资源', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('9', '变更单项资源', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('10', '创建酒店', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('11', '变更酒店', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('12', '创建团队产品', '1', '3', '1');
INSERT INTO `approval_type` VALUES ('13', '变更团队产品', '1', '3', '1');
INSERT INTO `approval_type` VALUES ('14', '创建用餐', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('15', '变更用餐', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('16', '创建路线模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('17', '变更路线模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('18', '创建航班', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('19', '变更航班', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('20', '创建路线类型', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('21', '变更路线模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('22', '创建航班', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('23', '变更航班', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('24', '创建回执单模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('25', '变更回执单模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('26', '创建邮轮', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('27', '变更邮轮', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('28', '创建线路模板', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('29', '变更线路类型', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('30', '创建签证', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('31', '变更签证', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('32', '创建订单', '1', '3', '1');
INSERT INTO `approval_type` VALUES ('33', '变更订单', '1', '3', '1');
INSERT INTO `approval_type` VALUES ('34', '创建景点', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('35', '变更景点', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('36', '创建代理', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('37', '变更代理', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('38', '创建车辆', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('39', '变更车辆', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('40', '创建地接报账', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('41', '变更地接报账', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('42', '创建分公司产品', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('43', '变更分公司产品', '1', '3', '0');
INSERT INTO `approval_type` VALUES ('44', '财务审批', '0', '1', '1');
INSERT INTO `approval_type` VALUES ('45', '团队产品-财务', '44', '2', '1');
INSERT INTO `approval_type` VALUES ('46', '创建应收', '45', '3', '1');
INSERT INTO `approval_type` VALUES ('47', '创建应付', '45', '3', '1');
INSERT INTO `approval_type` VALUES ('48', '变更应收', '45', '3', '1');
INSERT INTO `approval_type` VALUES ('49', '变更应付', '45', '3', '1');
INSERT INTO `approval_type` VALUES ('50', '订单管理-财务', '44', '2', '1');
INSERT INTO `approval_type` VALUES ('51', '创建应收', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('52', '创建销售收款', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('53', '创建应付', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('54', '变更销售收款', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('55', '变更应收', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('56', '变更应付', '50', '3', '1');
INSERT INTO `approval_type` VALUES ('57', '收款/付款', '44', '2', '1');
INSERT INTO `approval_type` VALUES ('58', '批量收款', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('59', '创建应收', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('60', '变更批量收款', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('61', '创建应付', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('62', '批量付款', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('63', '变更应收', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('64', '变更批量付款', '57', '3', '1');
INSERT INTO `approval_type` VALUES ('65', '变更应付', '57', '3', '1');
