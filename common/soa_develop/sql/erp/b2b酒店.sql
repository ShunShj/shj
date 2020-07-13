CREATE TABLE `b2b_hotel` (
  `b2b_hotel_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `hotel_name_cn` varchar(255) DEFAULT NULL COMMENT '中文name',
  `hotel_name_en` varchar(255) DEFAULT NULL COMMENT 'name英文名',
  `address_cn` varchar(255) DEFAULT NULL COMMENT '地址中文',
  `address_en` varchar(255) DEFAULT NULL COMMENT '地址英文',
  `country_id` int(10) DEFAULT NULL COMMENT '城市ID 第三级',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(11) DEFAULT NULL COMMENT '创建人ID',
  `update_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_user_id` int(11) DEFAULT NULL COMMENT '创建人ID',
  `status` tinyint(2) DEFAULT '1' COMMENT '1.开启 2.关闭',
  PRIMARY KEY (`b2b_hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;