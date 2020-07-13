CREATE TABLE `audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(150) NOT NULL COMMENT '操作员名',
  `model_name` varchar(150) NOT NULL COMMENT '模型名称',
  `model_id` int(11) NOT NULL COMMENT '模型的主键ID',
  `type` char(1) NOT NULL DEFAULT 'U' COMMENT '类型 C=创建 U=修改',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作记录 ';


CREATE TABLE `audit_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_id` int(11) NOT NULL,
  `field_name` varchar(32) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  PRIMARY KEY (`id`),
  KEY `fk_audit_detail_audit_id_idx` (`audit_id`),
  CONSTRAINT `fk_audit_detail_audit_id` FOREIGN KEY (`audit_id`) REFERENCES `audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

