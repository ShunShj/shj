/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-08-26 09:43:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company_finance_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_finance_approve`;
CREATE TABLE `company_finance_approve` (
  `company_finance_approve_id` int(10) NOT NULL AUTO_INCREMENT,
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '团队产品ID',
  `type` varchar(30) DEFAULT NULL COMMENT '1应收2应付',
  `object_company_id` int(11) DEFAULT NULL COMMENT '对方公司ID',
  `source_type_id` int(11) DEFAULT NULL COMMENT '资源类型',
  `product_name` varchar(300) DEFAULT NULL COMMENT '产品名称',
  `currency_id` int(11) DEFAULT NULL COMMENT '币种',
  `money` decimal(10,2) DEFAULT NULL COMMENT '金钱',
  `invoice_time` int(10) DEFAULT NULL COMMENT '发票时间',
  `invoice_number` varchar(30) DEFAULT NULL COMMENT '发票号',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
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
  PRIMARY KEY (`company_finance_approve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公司往来账审批';

-- ----------------------------
-- Records of company_finance_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_finance_customer`
-- ----------------------------
DROP TABLE IF EXISTS `company_finance_customer`;
CREATE TABLE `company_finance_customer` (
  `company_finance_customer_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应收ID',
  `company_finance_approve_id` int(30) DEFAULT NULL COMMENT '分公司往来账审批ID',
  `company_order_customer_id` int(10) DEFAULT NULL COMMENT '公司订单顾客ID',
  `customer_id` int(10) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1显示0删除',
  PRIMARY KEY (`company_finance_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分公司往来账游客';

-- ----------------------------
-- Records of company_finance_customer
-- ----------------------------
