/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-11-07 11:38:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `email_sms`
-- ----------------------------
DROP TABLE IF EXISTS `email_sms`;
CREATE TABLE `email_sms` (
  `email_sms_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '用户ID',
  `code` varchar(30) DEFAULT NULL,
  `type` int(10) DEFAULT NULL COMMENT '1重置密码',
  `email` varchar(200) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL COMMENT '时间戳',
  `status` tinyint(1) DEFAULT '1' COMMENT '1未使用2使用',
  PRIMARY KEY (`email_sms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of email_sms
-- ----------------------------
