/*
Navicat MySQL Data Transfer

Source Server         : 开发环境
Source Server Version : 50505
Source Host           : 192.168.11.122:3306
Source Database       : erp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-11-12 17:48:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `branch_product_comment`
-- ----------------------------
DROP TABLE IF EXISTS `branch_product_comment`;
CREATE TABLE `branch_product_comment` (
  `branch_product_comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `branch_product_number` varchar(30) DEFAULT NULL COMMENT '订单编号',
  `comment` text COMMENT '留言内容',
  `create_user_id` int(10) DEFAULT NULL COMMENT '创建人',
  `create_time` int(10) DEFAULT NULL COMMENT '上传时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态 1显示 0隐藏 -1删除',
  PRIMARY KEY (`branch_product_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单留言板表';

-- ----------------------------
-- Records of branch_product_comment
-- ----------------------------
