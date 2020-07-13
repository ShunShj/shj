
CREATE TABLE `ota_theme` (
  `ota_theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_diagram` varchar(255) DEFAULT NULL COMMENT '列表图',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `describe` varchar(9999) DEFAULT NULL COMMENT '描述',
  `file_name` varchar(255) NOT NULL COMMENT '文件名（路径）',
  `include_module` varchar(9999) DEFAULT NULL COMMENT '包含模块',
  `create_time` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1.显示 0 不显示',
  PRIMARY KEY (`ota_theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='主题';

-- ----------------------------
-- Records of ota_theme
-- ----------------------------

-- ----------------------------
-- Table structure for `ota_theme_module`
-- ----------------------------

CREATE TABLE `ota_theme_module` (
  `ota_theme_module_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主题模块',
  `ota_theme_id` int(11) NOT NULL COMMENT '主题ID',
  `ota_module_type_id` int(11) NOT NULL COMMENT '模块类型ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `describe` varchar(9999) DEFAULT NULL COMMENT '描述',
  `number_of_primary_keys` int(11) NOT NULL DEFAULT '1' COMMENT '组件的数量',
  `status` tinyint(2) NOT NULL COMMENT '1.显示 2.不显示',
  `layout_chart` varchar(255) DEFAULT NULL COMMENT '布局图',
  PRIMARY KEY (`ota_theme_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='主题模块(所有主题通模块名需一致)';


-- ----------------------------
-- Table structure for `ota_topic_page_module`
-- ----------------------------

CREATE TABLE `ota_topic_page_module` (
  `topic_page_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `ota_theme_module_id` int(11) NOT NULL COMMENT '主题模块ID',
  `subassembly_id` int(11) NOT NULL COMMENT '程序中定义死 1=>''''旅游产品'''',2=>''''旅游产品分类'''',3=>''''幻灯片'''',4=>''''文章'''',5=>''''文章分类'''',6=>''''广告位'''',7=>''''自定义代码'''',8=>''''菜单''''''',
  `position_n` int(11) NOT NULL COMMENT '页面中的位置 从1开始',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1.可以 2.不可用',
  PRIMARY KEY (`topic_page_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='主题模块分类';

-- ----------------------------
-- Records of ota_topic_page_module
-- ----------------------------
