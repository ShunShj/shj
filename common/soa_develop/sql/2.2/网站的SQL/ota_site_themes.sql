
CREATE TABLE `ota_site_themes` (
  `ota_site_theme_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `ota_theme_id` int(11) NOT NULL COMMENT '主题ID',
  `website_uuid` varchar(60) NOT NULL COMMENT '网站设置ID',
  PRIMARY KEY (`ota_site_theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='网站主题';

