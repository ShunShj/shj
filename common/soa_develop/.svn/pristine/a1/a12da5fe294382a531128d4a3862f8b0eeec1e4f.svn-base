/*
Navicat MySQL Data Transfer

Source Server         : 192.168.11.122
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-04-01 09:46:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `tag_id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) DEFAULT NULL,
  `language_id` int(10) DEFAULT NULL,
  `code_name` varchar(200) DEFAULT NULL,
  `type_name` varchar(100) DEFAULT NULL COMMENT '所属模块名称',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人ID',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  `update_user_id` int(10) DEFAULT NULL COMMENT '修改人ID',
  `status` int(11) DEFAULT NULL COMMENT '1启用0禁用',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
