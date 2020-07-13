/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-07-31 16:33:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `finance_approve`
-- ----------------------------
DROP TABLE IF EXISTS `finance_approve`;
CREATE TABLE `finance_approve` (
  `finance_approve_id` int(10) NOT NULL AUTO_INCREMENT,
  `company_order_number` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `finance_type` varchar(30) DEFAULT NULL COMMENT '财务类型1应收2应付',
  `finance_number` varchar(30) DEFAULT NULL COMMENT '财务编号可能为应收编号可能为应付编号',
  `voucher_number` varchar(30) DEFAULT NULL COMMENT '凭证编号',
  `voucher_time` int(10) DEFAULT NULL COMMENT '凭证时间',
  `money` decimal(10,2) DEFAULT NULL COMMENT '金额',
  `currency_id` int(10) DEFAULT NULL COMMENT '货币ID',
  `type` int(10) DEFAULT NULL COMMENT '支付方式1cash2check3debit card4credit card(mc)5credit card(vs)6 credit card(ax) 7 direct depsit 8 others',
  `number` varchar(30) DEFAULT NULL COMMENT '编号',
  `stage` int(10) DEFAULT NULL COMMENT '支付类型1 Balance 2 Deposit 3 Fullpmt 4 Gratuities 5 Insurance 6 Partpmt 7 Rebate 8 Tkt 9 Visa',
  `receivable_info_type` tinyint(4) DEFAULT NULL COMMENT '只有实收才会有 1普通2销售收款',
  `payment_time` int(10) DEFAULT NULL COMMENT '付款时间10位时间戳',
  `supplier_name` varchar(100) DEFAULT NULL COMMENT '供应商名称',
  `sn_number` varchar(100) DEFAULT NULL COMMENT 'SN号',
  `invoice_number` varchar(100) DEFAULT NULL COMMENT '发票编号',
  `pts` tinyint(1) DEFAULT '0' COMMENT '1是 0否',
  `exg_rate_gain` decimal(10,2) DEFAULT NULL COMMENT '汇兑损溢',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注 ',
  `account_number` varchar(300) DEFAULT NULL COMMENT 'AccountNumber',
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
  PRIMARY KEY (`finance_approve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of finance_approve
-- ----------------------------
