CREATE TABLE `company_order_comment` (
  `company_order_comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `company_order_number` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `comment` text COMMENT '留言内容',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人',
  `create_time` int(10) DEFAULT NULL COMMENT '上传时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态 1显示 0隐藏 -1删除',
  PRIMARY KEY (`company_order_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单留言板表';

