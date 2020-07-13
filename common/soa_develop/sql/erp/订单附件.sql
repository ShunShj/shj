CREATE TABLE `company_order_annex` (
  `company_order_annex_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `url` varchar(255) DEFAULT NULL COMMENT '附件地址',
  `create_user_id` int(10) DEFAULT NULL COMMENT '上传人',
  `create_time` int(10) DEFAULT NULL COMMENT '上传时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态 1显示 0隐藏 -1删除',
  PRIMARY KEY (`company_order_annex_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单附件表';

