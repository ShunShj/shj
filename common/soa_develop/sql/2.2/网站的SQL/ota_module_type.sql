
-- ----------------------------
-- Table structure for `ota_module_type`
-- ----------------------------

CREATE TABLE `ota_module_type` (
  `ota_module_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `html_file_name` varchar(255) NOT NULL COMMENT 'HTML的文件名',
  PRIMARY KEY (`ota_module_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='模块类型';

-- ----------------------------
-- Records of ota_module_type
-- ----------------------------
