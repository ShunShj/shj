ALTER TABLE `branch_product`
ADD COLUMN `type`  tinyint(2) NULL DEFAULT 1 COMMENT '类型 1、erp代售产品 2、b2b代售产品' AFTER `locked`;

CREATE TABLE `b2b_tour` (
  `btb_tour_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_tour` int(11) NOT NULL COMMENT '分公司产品ID',
  `is_inbound` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否入境 1.入境 2.不是入境',
  `tour_type1` int(11) NOT NULL DEFAULT '0' COMMENT '产品类型1级',
  `tour_type2` int(11) NOT NULL DEFAULT '0' COMMENT '产品类型2级',
  `local_category` int(11) DEFAULT '0',
  `tour_tab` int(11) DEFAULT NULL,
  `tour_code` varchar(255) DEFAULT NULL,
  `tour_name` varchar(255) DEFAULT NULL,
  `tour_name_ch` varchar(255) DEFAULT NULL,
  `length_days` tinyint(2) DEFAULT NULL COMMENT '几天',
  `length_nights` tinyint(2) DEFAULT NULL COMMENT '几晚',
  `frequency` varchar(10) DEFAULT NULL COMMENT '频率',
  `start_city` varchar(255) DEFAULT NULL COMMENT '开始城市',
  `end_city` varchar(255) DEFAULT NULL COMMENT '结束城市',
  `departure_airport` varchar(255) DEFAULT NULL COMMENT '出发机场',
  `arrival_airport` varchar(255) DEFAULT NULL COMMENT '到达机场',
  `country` varchar(255) DEFAULT NULL COMMENT '国家',
  `land_only` tinyint(2) DEFAULT '2' COMMENT '1 yes 2.No',
  `minimum_passenger` int(11) DEFAULT NULL COMMENT '最小参团人数',
  `pdf_flyer` varchar(255) DEFAULT NULL COMMENT 'PDF文件',
  `en_pdf_flyer` varchar(255) DEFAULT NULL,
  `terms` int(11) DEFAULT '0' COMMENT '条款',
  `booking_notice` int(11) DEFAULT '0' COMMENT '预定通知',
  `status` tinyint(2) DEFAULT '2' COMMENT '状态 1.Enable 2.Disable',
  `tour_languages` varchar(255) DEFAULT '0' COMMENT '语言',
  `infant` int(11) DEFAULT '0',
  `child` int(11) DEFAULT '0',
  `child_without_bed` int(11) DEFAULT '0',
  `service_charge_age` int(11) DEFAULT '0',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`btb_tour_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='B2B产品';







CREATE TABLE `b2b_tour_date` (
  `b2b_tour_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `arrival_date` date DEFAULT NULL COMMENT '到达日期',
  `departure_date` date DEFAULT NULL COMMENT '出发日期',
  `session_type` int(11) DEFAULT NULL,
  `office_contact` int(11) DEFAULT NULL,
  `note` varchar(999) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL COMMENT '1.开启 2.禁用',
  PRIMARY KEY (`b2b_tour_date_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='B2B产品发团日期';


CREATE TABLE `b2b_tour_itinerary` (
  `b2b_tour_itinerary_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `the_day` int(11) DEFAULT NULL,
  `hotel_cn` varchar(255) DEFAULT NULL,
  `hotel_en` varchar(255) DEFAULT NULL,
  `info_cn` text,
  `info_en` text,
  `status` tinyint(2) DEFAULT '2' COMMENT '1.开启 2.禁用',
  PRIMARY KEY (`b2b_tour_itinerary_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='B2B产品行程';

CREATE TABLE `b2b_tour_room` (
  `b2b_tour_room_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `room` tinyint(2) DEFAULT NULL COMMENT '房型',
  `capacity` varchar(255) DEFAULT NULL COMMENT '容量',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `status` tinyint(2) DEFAULT '2' COMMENT '1.开启 2.禁用',
  PRIMARY KEY (`b2b_tour_room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;



CREATE TABLE `b2b_tour_transfer` (
  `b2b_tour_transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `airport` varchar(255) DEFAULT NULL COMMENT '机场',
  `from` datetime DEFAULT NULL COMMENT '起飞时间',
  `to` datetime DEFAULT NULL COMMENT '到达时间',
  `min_pax` varchar(255) DEFAULT NULL COMMENT '最小人数',
  `type` tinyint(2) DEFAULT NULL,
  `note` text,
  `status` tinyint(2) DEFAULT '2' COMMENT '1.开启 2.禁用',
  PRIMARY KEY (`b2b_tour_transfer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;



CREATE TABLE `b2b_tour_commission` (
  `b2b_tour_commission_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `adult_grpss_cn` varchar(255) DEFAULT NULL,
  `adult_comm_cn` varchar(255) DEFAULT NULL,
  `adult_nett_cn` varchar(255) DEFAULT NULL,
  `child_wbed_gross_cn` varchar(255) DEFAULT NULL,
  `child_wbed_comm_cn` varchar(255) DEFAULT NULL,
  `child_wbed_nett_cn` varchar(255) DEFAULT NULL,
  `child_nbed_nett_cn` varchar(255) DEFAULT NULL,
  `adult_nett_en` varchar(255) DEFAULT NULL,
  `child_wbed_nett_en` varchar(255) DEFAULT NULL COMMENT '机场',
  `child_nbed_nett_en` varchar(255) DEFAULT NULL,
  `single_supp` varchar(255) DEFAULT NULL,
  `hotel_twin` varchar(255) DEFAULT NULL,
  `triple` varchar(255) DEFAULT NULL,
  `transfer` varchar(255) DEFAULT NULL,
  `infant` varchar(255) DEFAULT NULL,
  `tipping` varchar(255) DEFAULT NULL,
  `compulsory_program` varchar(255) DEFAULT NULL,
  `season_id` varchar(255) DEFAULT NULL,
  `inbound_quad_room_net` varchar(255) DEFAULT NULL,
  `inbound_triple_room_net` varchar(255) DEFAULT NULL,
  `inbound_twin_room_ent` varchar(255) DEFAULT NULL COMMENT '机场',
  `inbound_single_room_net` varchar(255) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL COMMENT '状态 1.Enable 2.Disable',
  `note` text,
  PRIMARY KEY (`b2b_tour_commission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



CREATE TABLE `b2b_tour_options` (
  `b2b_tour_options_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `cn_title` varchar(255) DEFAULT NULL,
  `en_title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `commission_precentage` varchar(255) DEFAULT NULL,
  `commission_fixed_rate` varchar(255) DEFAULT NULL,
  `cn_note` text,
  `en_note` text,
  `pay_by` tinyint(2) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`b2b_tour_options_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;



CREATE TABLE `b2b_tour_setting` (
  `b2b_tour_setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `btb_tour_id` int(11) DEFAULT NULL COMMENT '关联的btb_tour_id',
  `is_tour_voucher` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_pricing` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_tip` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_compulsory_program` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_optional_tip` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_season` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_age` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_ethnicity` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_child_with_bed` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `is_triple_surcharge` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  `dates_rule` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `inbound_team_emails` tinyint(2) DEFAULT NULL,
  `form_type` tinyint(2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL COMMENT '到达日期',
  PRIMARY KEY (`b2b_tour_setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

