#网站用户表
CREATE TABLE `ota_member` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `gender` char(1) DEFAULT NULL COMMENT '性别1男2女3其他',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像url',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `account` decimal(10,2) DEFAULT NULL COMMENT '账户',
  `company_id` int(10) DEFAULT NULL COMMENT '公司ID',
  `create_time` int(10) DEFAULT NULL COMMENT '注册时间',
  `update_time` int(10) DEFAULT NULL COMMENT 'x修改时间',
  `website_uuid` varchar(46) DEFAULT NULL COMMENT '网站的uuid',
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `uuid_only` (`uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='网站用户表';