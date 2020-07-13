CREATE TABLE `ota_website_pay` (
  `ota_website_pay_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `website_uuid` varchar(46) DEFAULT NULL COMMENT '网站的uuid',
  `pay_name` varchar(20) DEFAULT NULL COMMENT '支付方式名称',
  `pay_type` tinyint(10) DEFAULT NULL COMMENT '支付类型 1PayPal',
  `pay_desc` text COMMENT '支付账户信息',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态1开启 0关闭',
  PRIMARY KEY (`ota_website_pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
