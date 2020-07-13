/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-05 11:02:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company_order_accommodation_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_accommodation_approve`;
CREATE TABLE `company_order_accommodation_approve` (
  `company_order_accommodation_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分公司订单住宿ID',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '分公司订单编号',
  `company_order_customer_id` int(11) DEFAULT NULL COMMENT '公司订单顾客ID',
  `customer_id` varchar(30) DEFAULT NULL COMMENT '客户编号',
  `room_code` varchar(255) DEFAULT NULL COMMENT '房号',
  `room_type` tinyint(4) DEFAULT NULL COMMENT '1 双人房2大床房3单人房4加床',
  `remark` varchar(255) DEFAULT NULL,
  `check_in` varchar(10) DEFAULT NULL COMMENT '入住+1延后-1提前0正常',
  `check_on` varchar(10) DEFAULT NULL COMMENT '离开+1延后-1提前0正常',
  `check_in_hotel` varchar(300) DEFAULT NULL COMMENT '提前入住酒店，逗号隔开',
  `check_on_hotel` varchar(300) DEFAULT NULL COMMENT '延后退房酒店，多个逗号隔开',
  `create_time` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`company_order_accommodation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_accommodation_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_approve`;
CREATE TABLE `company_order_approve` (
  `company_order_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `order_name` varchar(500) DEFAULT NULL COMMENT '公司订单名称',
  `buy_order_time` int(10) DEFAULT NULL COMMENT '订购时间10位时间戳',
  `begin_time` int(10) DEFAULT NULL COMMENT '开始时间',
  `begin_city` varchar(50) DEFAULT NULL COMMENT '开始城市',
  `end_time` int(10) DEFAULT NULL COMMENT '结束时间',
  `wr` tinyint(4) DEFAULT NULL COMMENT '1retail 2wholesale',
  `clientsource` tinyint(4) DEFAULT NULL COMMENT '顾客来源',
  `channel_type` tinyint(4) DEFAULT NULL COMMENT '1经销商2直客',
  `distributor_id` int(10) DEFAULT NULL COMMENT '经销商ID',
  `description` text COMMENT '描述',
  `contect_name` varchar(30) DEFAULT NULL COMMENT '联系人',
  `tel` varchar(15) DEFAULT NULL COMMENT '联系人',
  `email` varchar(30) DEFAULT NULL COMMENT '邮箱',
  `persions_count` varchar(10) DEFAULT '0' COMMENT '人数',
  `remark` text COMMENT '备注',
  `company_id` int(10) DEFAULT NULL COMMENT '创建人的分公司ID',
  `operations_type_id` int(11) DEFAULT '0' COMMENT '待办类型',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间10位时间戳',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建用户ID',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间10位时间戳',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改用户ID',
  `locked` tinyint(4) DEFAULT '0' COMMENT '1锁定0没锁定',
  `status` tinyint(10) DEFAULT NULL COMMENT '1启用0禁用2暂存',
  PRIMARY KEY (`company_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_customer_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_customer_approve`;
CREATE TABLE `company_order_customer_approve` (
  `company_order_customer_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单客户ID',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `customer_id` int(10) DEFAULT NULL COMMENT '顾客编号 0为占位',
  `special_claim` varchar(300) DEFAULT NULL COMMENT '特殊要求',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人ID',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改人ID',
  `status` int(10) DEFAULT NULL COMMENT '0禁用1未确认2占位3已确认',
  PRIMARY KEY (`company_order_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_customer_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_customer_lineup_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_customer_lineup_approve`;
CREATE TABLE `company_order_customer_lineup_approve` (
  `company_order_customer_lineup_id` int(10) NOT NULL AUTO_INCREMENT,
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '公司订单编号',
  `company_order_id` int(11) DEFAULT NULL COMMENT '公司订单ID',
  `team_product_number` varchar(30) DEFAULT NULL COMMENT '团队产品编号',
  `team_product_id` int(10) DEFAULT NULL COMMENT '团队产品ID',
  `lineup_type` int(10) DEFAULT NULL COMMENT '1订单2团队产品',
  `lineup_number` int(10) DEFAULT NULL COMMENT '编号',
  `company_order_customer_id` int(10) DEFAULT NULL COMMENT '公司订单客户ID',
  `customer_id` int(11) DEFAULT NULL COMMENT '客户ID',
  `create_user_id` int(10) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_user_id` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`company_order_customer_lineup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of company_order_customer_lineup_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_flight_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_flight_approve`;
CREATE TABLE `company_order_flight_approve` (
  `company_order_flight_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分公司订单航班主键',
  `company_order_number` varchar(30) DEFAULT NULL,
  `company_order_customer_id` int(11) DEFAULT NULL COMMENT '公司订单客户ID',
  `customer_id` varchar(30) DEFAULT NULL,
  `flight_code` varchar(30) DEFAULT NULL COMMENT '航班号',
  `flight_begin_time` int(10) DEFAULT NULL COMMENT '航班开始时间',
  `flight_end_time` int(10) DEFAULT NULL COMMENT '航班结束时间',
  `flight_type` tinyint(4) DEFAULT NULL COMMENT '1接机2送机3中转',
  `start_place` varchar(30) DEFAULT NULL COMMENT '出发地',
  `end_place` varchar(30) DEFAULT NULL COMMENT '目的地',
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`company_order_flight_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_flight_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_product_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_approve`;
CREATE TABLE `company_order_product_approve` (
  `company_order_product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分公司订单产品主键',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '分公司订单编号',
  `branch_product_number` varchar(50) DEFAULT NULL COMMENT '分公司产品编号',
  `branch_product_name` varchar(50) DEFAULT NULL COMMENT '分公司产品名称',
  `branch_product_cost` decimal(10,2) DEFAULT NULL COMMENT '成本',
  `supplier_name` varchar(30) DEFAULT NULL COMMENT '供应商名称',
  `cost_currency_id` int(10) DEFAULT NULL COMMENT '成本货币ID',
  `branch_product_price` decimal(10,2) DEFAULT NULL COMMENT '报价',
  `price_currency_id` int(10) DEFAULT NULL COMMENT '报价货币ID',
  `tax_id` int(10) DEFAULT NULL COMMENT '税ID',
  `price_before_tax` decimal(10,2) DEFAULT NULL COMMENT '税前报价',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `receivable_number` varchar(30) DEFAULT NULL COMMENT '应收编号',
  `create_user_id` int(11) DEFAULT NULL COMMENT '创建人id',
  `update_user_id` int(11) DEFAULT NULL COMMENT '修改人ID',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`company_order_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_product_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_product_diy_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_diy_approve`;
CREATE TABLE `company_order_product_diy_approve` (
  `company_order_product_diy_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分公司订单产品主键',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '公司订单编号',
  `company_order_product_diy_number` varchar(30) DEFAULT NULL COMMENT '公司订单DIY编号',
  `diy_name` varchar(30) DEFAULT NULL COMMENT 'DIY名称',
  `diy_price` decimal(10,2) DEFAULT NULL COMMENT 'DIY报价',
  `diy_cost` decimal(10,2) DEFAULT NULL COMMENT '成本',
  `diy_cost_univalence` decimal(10,2) DEFAULT NULL COMMENT '成本单价',
  `supplier_id` int(11) DEFAULT NULL COMMENT '供应商ID',
  `invoice_number` varchar(30) DEFAULT NULL COMMENT '发票单号',
  `cost_currency_id` int(11) DEFAULT NULL COMMENT '成本货币ID',
  `price_currency_id` int(11) DEFAULT NULL COMMENT '报价货币ID',
  `invoice_time` int(10) DEFAULT NULL COMMENT '发票日期 10位时间戳',
  `tax_id` int(10) DEFAULT NULL COMMENT '税',
  `tax_cd` varchar(30) DEFAULT NULL COMMENT '税号',
  `price_before_tax` decimal(10,2) DEFAULT NULL COMMENT '税前报价',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `cope_number` varchar(30) DEFAULT NULL COMMENT '应付编号',
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
  PRIMARY KEY (`company_order_product_diy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_product_diy_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_product_source_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_source_approve`;
CREATE TABLE `company_order_product_source_approve` (
  `company_order_product_source_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分公司订单产品主键',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '分公司订单编号',
  `branch_product_number` varchar(50) DEFAULT NULL COMMENT '分公司产品编号',
  `team_product_number` varchar(30) DEFAULT NULL COMMENT '团队产品编号',
  `team_product_id` int(10) DEFAULT NULL COMMENT '团队产品ID',
  `team_product_allocation_id` int(10) DEFAULT NULL COMMENT '团队产品资源配置ID',
  `supplier_type_id` int(10) DEFAULT NULL COMMENT '资源类型',
  `supplier_name` varchar(30) DEFAULT NULL,
  `source_id` int(10) DEFAULT NULL COMMENT '资源主键',
  `source_name` varchar(50) DEFAULT NULL COMMENT '资源名称',
  `source_cost_univalence` decimal(10,2) DEFAULT '0.00' COMMENT '成本单价',
  `source_cost` decimal(10,2) DEFAULT NULL COMMENT '成本',
  `source_price` decimal(10,2) DEFAULT NULL COMMENT '报价',
  `cost_currency_id` int(11) DEFAULT NULL COMMENT '成本货币ID',
  `price_currency_id` int(11) DEFAULT NULL COMMENT '报价货币ID',
  `invoice_number` varchar(255) DEFAULT NULL COMMENT '发票号',
  `invoice_time` int(11) DEFAULT NULL COMMENT '发票时间10位时间戳',
  `tax_id` int(10) DEFAULT NULL COMMENT '税ID',
  `tax_cd` varchar(30) DEFAULT NULL COMMENT '税号',
  `price_before_tax` decimal(10,2) DEFAULT NULL COMMENT '税前报价',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `cope_number` varchar(30) DEFAULT NULL COMMENT '应付编号',
  `receivable_number` varchar(30) DEFAULT NULL COMMENT '应收编号',
  `is_receivable_company` tinyint(4) DEFAULT '2' COMMENT '是否是应收分公司过来的数据1是2不是',
  `team_product_receivable_type` tinyint(4) DEFAULT '1' COMMENT '团队产品应收类型1团队产品2其他',
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
  PRIMARY KEY (`company_order_product_source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_product_source_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_product_team_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_team_approve`;
CREATE TABLE `company_order_product_team_approve` (
  `company_order_product_team_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分公司订单产品团队主键',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '分公司订单编号',
  `team_product_number` varchar(50) DEFAULT NULL COMMENT '团队产品编号',
  `team_product_id` int(10) DEFAULT NULL COMMENT '团队产品ID',
  `team_product_name` varchar(300) DEFAULT NULL COMMENT '团队产品名称',
  `branch_product_number` varchar(30) DEFAULT NULL COMMENT '分公司产品编号',
  `settlement_type` tinyint(4) DEFAULT NULL COMMENT '结算方式1 1口价 2真实结算',
  `team_product_price` decimal(10,2) DEFAULT NULL COMMENT '团队产品报价',
  `team_product_cost` decimal(10,2) DEFAULT NULL COMMENT '团队产品成本',
  `team_product_cost_univalence` decimal(10,2) DEFAULT NULL COMMENT '团队产品成本单价',
  `supplier_name` varchar(30) DEFAULT NULL,
  `cost_currency_id` int(10) DEFAULT NULL COMMENT '成本货币ID',
  `price_currency_id` int(11) DEFAULT NULL COMMENT '报价货币ID',
  `invoice_number` varchar(30) DEFAULT NULL COMMENT '发票号',
  `invoice_time` int(11) DEFAULT NULL COMMENT '发票时间10位时间戳',
  `tax_id` int(10) DEFAULT NULL COMMENT '税ID',
  `tax_cd` varchar(30) DEFAULT NULL COMMENT '税号',
  `price_before_tax` decimal(10,2) DEFAULT NULL COMMENT '税前价格',
  `receivable_number` varchar(30) DEFAULT NULL COMMENT '对应应收编号',
  `cope_number` varchar(30) DEFAULT NULL COMMENT '对应应付编号',
  `is_receivable_company` tinyint(4) DEFAULT '2' COMMENT '是否是应收分公司过来的数据1是2不是',
  `team_product_receivable_type` tinyint(4) DEFAULT '1' COMMENT '团队产品应收类型1团队产品2其他',
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
  PRIMARY KEY (`company_order_product_team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_product_team_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_product_template_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_product_template_approve`;
CREATE TABLE `company_order_product_template_approve` (
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
-- Records of company_order_product_template_approve
-- ----------------------------

-- ----------------------------
-- Table structure for `company_order_relation_approve`
-- ----------------------------
DROP TABLE IF EXISTS `company_order_relation_approve`;
CREATE TABLE `company_order_relation_approve` (
  `company_order_relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '公司订单编号',
  `company_order_customer_id` int(11) DEFAULT NULL COMMENT '顾客表 ID',
  `company_order_product_source_id` int(11) DEFAULT NULL COMMENT '主键ID',
  `company_order_product_diy_id` int(11) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`company_order_relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_order_relation_approve
-- ----------------------------

#添加审核状态字段
ALTER TABLE `company_order`
ADD COLUMN `is_approve`  tinyint NULL DEFAULT 0 COMMENT '1审核中0未审核' AFTER `locked`;


