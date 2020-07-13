DROP TABLE IF EXISTS `limits_for_examinity_and_approving_authority`;
CREATE TABLE `limits_for_examinity_and_approving_authority` (
  `limits_for_examinity_and_approving_authority_id` int(11) NOT NULL AUTO_INCREMENT,
  `approval_type_id` int(11) NOT NULL COMMENT '审批类型',
  `role_id` int(11) NOT NULL COMMENT '角色',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1开启 2关闭',
  PRIMARY KEY (`limits_for_examinity_and_approving_authority_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='审批权限';

