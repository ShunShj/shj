#创建产品分类表
DROP TABLE IF EXISTS `ota_product_type`;
CREATE TABLE `ota_product_type` (
  `ota_product_type_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `type_name` varchar(30) DEFAULT NULL COMMENT '产品分类名称',
  `type_lv` tinyint(4) DEFAULT NULL COMMENT '分类等级',
  `pid` int(10) DEFAULT NULL COMMENT '上级pid',
  `company_id` int(10) DEFAULT NULL COMMENT '分公司id',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人uid',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改人',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  `website_uuid` varchar(46) DEFAULT NULL COMMENT '网站的uuid',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 1启用 2停用',
  PRIMARY KEY (`ota_product_type_id`),
  UNIQUE KEY `uuid_only` (`uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='产品分类表';

#创建旅游产品分类下的目的地表
DROP TABLE IF EXISTS `ota_product_type_destination`;
CREATE TABLE `ota_product_type_destination` (
  `ota_product_type_destination_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '目的地id',
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `destination_name` varchar(255) DEFAULT NULL COMMENT '目的地名称',
  `ota_type_product_uuid` varchar(46) DEFAULT NULL COMMENT '关联的产品分类的uuid',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 1启用 2停用',
  PRIMARY KEY (`ota_product_type_destination_id`),
  UNIQUE KEY `uuid_only` (`uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='旅游产品分类下的目的地表';

#创建旅游产品分类下的景点表
DROP TABLE IF EXISTS `ota_product_type_scenic_spot`;
CREATE TABLE `ota_product_type_scenic_spot` (
  `ota_product_type_scenic_spot_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '景点id',
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `scenic_spot_name` varchar(255) DEFAULT NULL COMMENT '景点名称',
  `ota_type_product_uuid` varchar(46) DEFAULT NULL COMMENT '关联的产品分类的uuid',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 1启用 2停用',
  PRIMARY KEY (`ota_product_type_scenic_spot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='旅游产品分类下的景点表';


#创建产品和分类关联表
DROP TABLE IF EXISTS `ota_product_types`;
CREATE TABLE `ota_product_types` (
  `ota_product_types_id` int(10) NOT NULL AUTO_INCREMENT,
  `product_uuid` varchar(46) DEFAULT NULL COMMENT '产品id',
  `product_type_uuid` varchar(46) DEFAULT NULL COMMENT '对应的产品分类表uuid',
  PRIMARY KEY (`ota_product_types_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='产品和分类关联表';


#创建旅游产品主表
DROP TABLE IF EXISTS `ota_product`;
CREATE TABLE `ota_product` (
  `ota_product_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `product_tag` varchar(255) DEFAULT NULL COMMENT '标签',
  `sorting` int(10) DEFAULT NULL COMMENT '排序',
  `pviews` int(10) DEFAULT NULL COMMENT '阅读量',
  `visibility` tinyint(1) DEFAULT NULL COMMENT '可见性 1.代理、直客均可见 2.仅直客可见 3.仅代理可见',
  `company_id` int(10) DEFAULT NULL COMMENT '公司ID',
  `create_time` int(10) DEFAULT NULL COMMENT '注册时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人uid',
  `update_time` int(10) DEFAULT NULL COMMENT 'x修改时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改人',
  `website_uuid` varchar(46) DEFAULT NULL COMMENT '网站的uuid',
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`ota_product_id`),
  UNIQUE KEY `uuid_only` (`uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='旅游产品主表';

#创建旅游产品介绍表
DROP TABLE IF EXISTS `ota_product_info`;
CREATE TABLE `ota_product_info` (
  `ota_product_info_id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL,
  `cover_image` varchar(200) DEFAULT NULL COMMENT '封面图片',
  `product_description` text COMMENT '产品简介',
  `product_content` text COMMENT '详细介绍',
  `create_time` int(10) DEFAULT NULL,
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '关联的旅游产品UUID',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建用户ID',
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ota_product_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='旅游产品介绍表';

#创建旅游产品介绍表--图片表
DROP TABLE IF EXISTS `ota_product_image`;
CREATE TABLE `ota_product_image` (
  `ota_product_image_id` int(10) NOT NULL AUTO_INCREMENT,
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '旅游产品UUID',
  `image_url` varchar(255) DEFAULT NULL COMMENT '视频地址',
  PRIMARY KEY (`ota_product_image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='旅游产品介绍表--图片表';

#创建旅游产品介绍表--视频表
DROP TABLE IF EXISTS `ota_product_video`;
CREATE TABLE `ota_product_video` (
  `ota_product_vedio_id` int(10) NOT NULL AUTO_INCREMENT,
  `video_url` varchar(255) DEFAULT NULL COMMENT '视频地址',
  `video_type` varchar(255) DEFAULT NULL COMMENT '视频类型',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '旅游产品UUID',
  PRIMARY KEY (`ota_product_vedio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='旅游产品介绍表--视频表';


#创建旅游产品订购须知&费用明细表
DROP TABLE IF EXISTS `ota_product_introduce`;
CREATE TABLE `ota_product_introduce` (
  `product_introduce_id` int(10) NOT NULL AUTO_INCREMENT,
  `ota_product_uuid` varchar(46) DEFAULT NULL,
  `cost_detail` text COMMENT '费用明细',
  `ordering_information` text COMMENT '订购须知 ',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`product_introduce_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='旅游产品订购须知&费用明细表';


#创建旅游产品行程表
DROP TABLE IF EXISTS `ota_product_journey`;
CREATE TABLE `ota_product_journey` (
  `ota_product_journey_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '团队产品 行程ID',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '旅游产品uuid',
  `the_days` tinyint(4) DEFAULT NULL COMMENT '第几天',
  `country_id` int(10) DEFAULT NULL COMMENT '地区id',
  `route_journey_title` varchar(300) DEFAULT NULL COMMENT '行程标题',
  `route_journey_content` text COMMENT '行程内容',
  `route_journey_traffic` varchar(500) DEFAULT NULL COMMENT '交通',
  `route_journey_stay` varchar(150) DEFAULT NULL COMMENT '住宿',
  `eat_mark` varchar(20) DEFAULT NULL COMMENT '吃饭标注,1,2,3 1早饭2午饭3晚饭 逗号分隔',
  `route_journey_breakfast` varchar(60) DEFAULT NULL COMMENT '早餐',
  `route_journey_lunch` varchar(60) DEFAULT NULL COMMENT '午餐',
  `route_journey_dinner` varchar(60) DEFAULT NULL COMMENT '晚餐',
  `route_journey_scenic_sport` varchar(255) DEFAULT NULL COMMENT '景点',
  `route_journey_picture` text COMMENT '图片 多个用逗号分隔',
  `route_journey_remark` text COMMENT '备注',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建用户ID',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改用户ID',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态1启用0禁用',
  PRIMARY KEY (`ota_product_journey_id`),
  KEY `team_product_id` (`ota_product_uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='旅游产品行程表';

#创建旅游产品行程--航班信息表
DROP TABLE IF EXISTS `ota_product_flight`;
CREATE TABLE `ota_product_flight` (
  `product_flight_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '路线航班ID',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '电商产品UUID',
  `the_days` int(10) DEFAULT NULL COMMENT '第几天',
  `start_city` varchar(10) DEFAULT NULL COMMENT '出发地',
  `end_city` varchar(10) DEFAULT NULL COMMENT '目的地',
  `start_time` int(10) DEFAULT NULL COMMENT '出发时间10位时间戳',
  `end_time` int(10) DEFAULT NULL COMMENT '到达时间10位时间戳',
  `flight_number` varchar(30) DEFAULT NULL COMMENT '航班号',
  `flight_type` tinyint(4) DEFAULT NULL COMMENT '1接机2送机3中转',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态1启用0禁用',
  PRIMARY KEY (`product_flight_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COMMENT='旅游产品行程--航班信息表';


#创建旅游产品规格表
DROP TABLE IF EXISTS `ota_product_specifications`;
CREATE TABLE `ota_product_specifications` (
  `ota_product_specifications_id` int(10) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '关联的旅游产品的uuid',
  `product_specifications_name` varchar(200) DEFAULT NULL COMMENT '规格',
  `branch_product_id` int(10) DEFAULT NULL COMMENT '分公司产品ID',
  `create_user_id` int(10) DEFAULT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '状态1启用0禁用',
  PRIMARY KEY (`ota_product_specifications_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='旅游产品规格表';

#创建旅游产品规格--团队产品价格表
DROP TABLE IF EXISTS `ota_product_team_product`;
CREATE TABLE `ota_product_team_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `product_specifications_uuid` varchar(46) DEFAULT NULL COMMENT '产品的规格ID',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '产品的ID',
  `team_product_id` int(10) DEFAULT NULL COMMENT '团队产品ID',
  `customer_price` decimal(10,2) DEFAULT NULL COMMENT '直客报价',
  `distributor_price` decimal(10,2) DEFAULT NULL COMMENT '代理价格',
  `currency_id` int(10) DEFAULT NULL COMMENT '货币ID',
  `original_price` decimal(10,2) DEFAULT NULL COMMENT '原价',
  `original_currency_id` int(10) DEFAULT NULL COMMENT '原价货币id',
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='旅游产品规格--团队产品价格表';

#创建旅游产品规格--自费资源表
DROP TABLE IF EXISTS `ota_product_source`;
CREATE TABLE `ota_product_source` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(46) DEFAULT NULL COMMENT 'uuid',
  `product_specifications_uuid` varchar(46) DEFAULT NULL COMMENT '产品的规格ID',
  `ota_product_uuid` varchar(46) DEFAULT NULL COMMENT '产品的ID',
  `source_id` int(10) DEFAULT NULL COMMENT '资源ID',
  `source_type_id` int(10) DEFAULT NULL COMMENT '资源类型ID  ',
  `customer_price` decimal(10,2) DEFAULT NULL COMMENT '报价',
  `distributor_price` decimal(10,2) DEFAULT NULL COMMENT '代理价格',
  `currency_id` int(10) DEFAULT NULL COMMENT '货币ID',
  `original_price` decimal(10,2) DEFAULT NULL COMMENT '原价',
  `original_currency_id` int(10) DEFAULT NULL COMMENT '原价货币id',
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='旅游产品规格--自费资源表';


#创建产品与分类下目的地关联表
DROP TABLE IF EXISTS `ota_product_destination`;
CREATE TABLE `ota_product_destination` (
  `ota_product_uuid` varchar(46) NOT NULL COMMENT '关联的旅游产品UUID',
  `ota_product_type_destination_uuid` varchar(46) NOT NULL COMMENT '产品分类下目的地uuid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品与分类下目的地关联表';

#创建产品与分类下景点关联表
DROP TABLE IF EXISTS `ota_product_scenic_spot`;
CREATE TABLE `ota_product_scenic_spot` (
  `ota_product_uuid` varchar(46) NOT NULL COMMENT '关联的旅游产品UUID',
  `ota_product_type_scenic_spot_uuid` varchar(46) NOT NULL COMMENT '产品分类下目景点uuid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品与分类下景点关联表';







#修改数据表结构语句
ALTER TABLE `ota_product_specifications`
MODIFY COLUMN `branch_product_id`  int(10) NULL DEFAULT NULL COMMENT '分公司产品ID （分公司产品时有）' AFTER `product_specifications_name`,
ADD COLUMN `route_template_id`  int(10) NULL COMMENT '线路模板id (团队产品时有)' AFTER `status`,
ADD COLUMN `product_type`  tinyint(1) NULL COMMENT '产品类型 1是分公司产品 2是团队产品' AFTER `route_template_id`;


ALTER TABLE `ota_product_source`
ADD COLUMN `team_product_allocation_id`  int(10) NULL COMMENT '团队产品资源配置ID' AFTER `team_product_id`;

ALTER TABLE `ota_product`
ADD COLUMN `is_del`  tinyint(4) NULL COMMENT '是否删除' AFTER `status`,
ADD COLUMN `delete_user_id`  tinyint(4) NULL COMMENT '删除人' AFTER `is_del`,
ADD COLUMN `delete_time`  int(10) NULL COMMENT '删除时间' AFTER `delete_user_id`;

ALTER TABLE `ota_product_info`
ADD COLUMN `map_url`  varchar(200) NULL COMMENT '地图url' AFTER `cover_image`;

ALTER TABLE `ota_product_info`
ADD COLUMN `annex_url`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '附件的url' AFTER `cover_image`;


ALTER TABLE `ota_product_info`
MODIFY COLUMN `cover_image`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面图片' AFTER `uuid`,
MODIFY COLUMN `annex_url`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '附件的url' AFTER `cover_image`,
MODIFY COLUMN `map_url`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地图url' AFTER `annex_url`,
ADD COLUMN `traffic_icon`  varchar(20) NULL COMMENT '交通工具图标' AFTER `map_url`,
ADD COLUMN `slogan`  varchar(1000) NULL COMMENT '标语' AFTER `traffic_icon`,
ADD COLUMN `aviation_icon`  varchar(1000) NULL COMMENT '航空公司图标' AFTER `slogan`;




