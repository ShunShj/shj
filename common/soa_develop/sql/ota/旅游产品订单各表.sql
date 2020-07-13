#修改订单主表
ALTER TABLE `company_order`
ADD COLUMN `order_status`  tinyint(2) NULL DEFAULT NULL COMMENT '官网订单的状态 0暂存 1待支付 2待确认 3待成团 4成团 5结团 6退款中 7已取消' AFTER `status`,
ADD COLUMN `ota_members_uuid`  varchar(46) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关联的用户uuid' AFTER `order_status`,
ADD COLUMN `website_uuid`  varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网站订单时 网站的uuid' AFTER `ota_members_uuid`,
ADD COLUMN `product_uuid`  varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '网站订单时 选择的旅游产品的uuid' AFTER `website_uuid`,
ADD COLUMN `spec_uuid`  varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '网站订单时 选择的旅游产品的规格的uuid' AFTER `product_uuid`,
ADD COLUMN `ota_product_team_uuid`  varchar(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '网站订单时 选择的旅游产品的规格的团队产品uuid（发团日期不同' AFTER `spec_uuid`;


#添加网站订单时间表
CREATE TABLE `ota_order_time` (
  `company_order_number` varchar(30) CHARACTER SET utf8mb4 NOT NULL COMMENT '订单编号',
  `create_time` int(11) DEFAULT NULL COMMENT '订单创建时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '订单支付时间  订单状态更改为2 待确认',
  `confirm_time` int(11) DEFAULT NULL COMMENT '订单确认时间 订单状态更改为3 待成团',
  `groups_time` int(11) DEFAULT NULL COMMENT '订单成团时间 订单状态更改为4 成团',
  `clear_group_time` int(11) DEFAULT NULL COMMENT '订单结团时间 订单状态更改为5 结团',
  `refund_time` int(11) DEFAULT NULL COMMENT '订单申请退款中时间 订单状态更改为6 退款中',
  `cancel_time` int(11) DEFAULT NULL COMMENT '订单取消时间 订单状态更改为7已取消',
  PRIMARY KEY (`company_order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站订单时各状态时间记录表';


#订单支付信息记录表
CREATE TABLE `order_pay_record` (
  `order_pay_record_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `payer_id` varchar(255) DEFAULT NULL COMMENT '买方 paypal id',
  `payer_email` varchar(255) DEFAULT NULL COMMENT '买方邮件',
  `payment_date` varchar(30) DEFAULT NULL COMMENT 'PayPal生成的格式的时间和日期',
  `first_name` varchar(255) DEFAULT NULL COMMENT '客户的名字',
  `last_name` varchar(255) DEFAULT NULL COMMENT '客户姓氏',
  `receiver_email` varchar(255) DEFAULT NULL COMMENT '收款方邮件 付款收件人（即商家）的主电子邮件地址',
  `business` varchar(255) DEFAULT NULL COMMENT '收款方 电子邮件地址或帐户 ID支付接收者（即商家）',
  `mc_gross` decimal(10,2) DEFAULT NULL COMMENT '扣除交易费之前，全额支付客户的款项。',
  `mc_fee` decimal(10,2) DEFAULT NULL COMMENT 'paypal 手续费',
  `mc_currency` varchar(10) DEFAULT NULL COMMENT '付款的货币',
  `receiver_id` varchar(255) DEFAULT NULL COMMENT '商家id  独特的帐户 ID支付接收者（即商家）。',
  `company_order_number` varchar(30) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '关联的订单编号',
  `payment_status` varchar(50) DEFAULT NULL COMMENT '支付状态',
  `pending_reason` varchar(255) DEFAULT NULL,
  `txn_id` varchar(20) DEFAULT NULL COMMENT '交易号',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`order_pay_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付信息记录表';



ALTER TABLE `order_pay_record`
ADD COLUMN `payment_status`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付状态' AFTER `company_order_number`,
ADD COLUMN `pending_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `payment_status`;

