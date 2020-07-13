/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-07 18:05:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audits`
-- ----------------------------
DROP TABLE IF EXISTS `audits`;
CREATE TABLE `audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(150) NOT NULL COMMENT '操作员名',
  `model_name` varchar(150) NOT NULL COMMENT '模型名称',
  `model_id` int(11) NOT NULL COMMENT '模型的主键ID',
  `type` char(1) NOT NULL DEFAULT 'U' COMMENT '类型 C=创建 U=修改',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作记录 ';

-- ----------------------------
-- Records of audits
-- ----------------------------
