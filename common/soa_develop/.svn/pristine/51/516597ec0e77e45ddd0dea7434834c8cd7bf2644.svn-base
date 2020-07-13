/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-08-19 16:26:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `statement_approve`
-- ----------------------------
DROP TABLE IF EXISTS `statement_approve`;
CREATE TABLE `statement_approve` (
  `statement_approve_id` int(10) NOT NULL AUTO_INCREMENT,
  `team_product_id` int(30) DEFAULT NULL COMMENT '团队产品ID',
  `type` varchar(30) DEFAULT NULL COMMENT '类型1 收供应商2收分公司3收直客/代理4付供应商5付分公司',
  `json_data` text COMMENT 'json数据',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `approve_result` tinyint(4) DEFAULT NULL COMMENT '1通过2未通过',
  `approve_opinion` varchar(300) DEFAULT NULL COMMENT '审批意见',
  `approve_time` int(10) DEFAULT NULL COMMENT '审批时间',
  `approve_user_id` int(10) DEFAULT NULL COMMENT '审批人ID',
  `company_id` int(10) DEFAULT NULL COMMENT '所属公司ID',
  `status` int(11) DEFAULT NULL COMMENT '1审批中2审批结束',
  PRIMARY KEY (`statement_approve_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='结算单审批';

-- ----------------------------
-- Records of statement_approve
-- ----------------------------
INSERT INTO `statement_approve` VALUES ('31', '0', '1', 'RC-256', '1565842093', '1565842093', '91', '91', '1', '3', '1565842112', '1', '3', '2');
INSERT INTO `statement_approve` VALUES ('34', '0', '1', 'RC-256', '1565853175', '1565853175', '91', '91', '2', '3', '1565853297', '1', '3', '2');
INSERT INTO `statement_approve` VALUES ('35', '0', '1', 'RC-256', '1565853326', '1565853326', '91', '91', '1', '1', '1565853343', '1', '3', '2');
INSERT INTO `statement_approve` VALUES ('36', '0', '1', 'RC-256', '1565853335', '1565853335', '91', '91', null, null, null, null, '3', '1');
