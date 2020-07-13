/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-11-07 11:39:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company_order_product_diy_price`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_diy_price`;
CREATE TABLE `company_order_product_diy_price` (
  `company_order_product_diy_price_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分公司订单产品主键',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '公司订单编号',
  `diy_type` tinyint(1) DEFAULT NULL COMMENT '1代售产品2酒店3用餐4航班5邮轮6签证7景点8车辆9导游10单项资源11自费12购物店13其他14团费',
  `diy_name` varchar(30) DEFAULT NULL COMMENT 'DIY名称',
  `diy_price` decimal(10,2) DEFAULT NULL COMMENT 'DIY报价',
  `price_currency_id` int(11) DEFAULT NULL COMMENT '报价货币ID',
  `invoice_time` int(10) DEFAULT NULL COMMENT '发票日期 10位时间戳',
  `tax_price` decimal(10,2) DEFAULT NULL COMMENT '税费',
  `tax_id` int(10) DEFAULT NULL COMMENT '税',
  `price_before_tax` decimal(10,2) DEFAULT NULL COMMENT '税前报价',
  `substribe_time` int(10) DEFAULT NULL COMMENT '订阅时间',
  `utc_substribe_time` int(10) DEFAULT NULL COMMENT '订阅时间',
  `receivable_number` varchar(30) DEFAULT NULL COMMENT '应收编号',
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  `netamt` varchar(30) DEFAULT NULL COMMENT 'NetAmt',
  `gst` varchar(30) DEFAULT NULL COMMENT 'GST',
  `pst` varchar(30) DEFAULT NULL,
  `hst` varchar(30) DEFAULT NULL,
  `p_otx` varchar(30) DEFAULT NULL COMMENT 'OTX',
  `invamt` varchar(30) DEFAULT NULL COMMENT 'InvAmt',
  `estcost` varchar(30) DEFAULT NULL COMMENT 'EstCost',
  `paidamt` varchar(30) DEFAULT NULL COMMENT 'PaidAmt',
  `balance` varchar(30) DEFAULT NULL COMMENT 'Balance',
  PRIMARY KEY (`company_order_product_diy_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_product_diy_price
-- ----------------------------
ALTER TABLE `company_order_product_diy_price`
ADD COLUMN `remark`  varchar(300) NULL COMMENT '备注' AFTER `price_before_tax`;

