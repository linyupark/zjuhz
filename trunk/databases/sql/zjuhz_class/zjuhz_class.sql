/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_class
Target Host: localhost
Target Database: zjuhz_class
Date: 2008-4-21 17:02:05
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_class
-- ----------------------------
CREATE TABLE `tbl_class` (
  `class_id` int(10) unsigned NOT NULL auto_increment,
  `class_name` char(50) NOT NULL,
  `class_college` char(50) NOT NULL,
  `class_year` smallint(4) unsigned NOT NULL,
  `class_create_time` int(11) NOT NULL,
  `class_charge` int(10) unsigned NOT NULL,
  `class_notice` text,
  `class_member_num` smallint(6) unsigned NOT NULL default '1',
  PRIMARY KEY  (`class_id`),
  UNIQUE KEY `className` (`class_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_apply
-- ----------------------------
CREATE TABLE `tbl_class_apply` (
  `class_apply_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_id` int(10) unsigned NOT NULL,
  `class_member_id` int(10) unsigned NOT NULL,
  `class_apply_content` tinytext,
  `class_apply_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`class_apply_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_member
-- ----------------------------
CREATE TABLE `tbl_class_member` (
  `class_member_id` int(10) unsigned NOT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `class_member_join_time` int(10) unsigned NOT NULL,
  `class_member_status` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:正常 1:忙碌 2:离开',
  `class_member_nickname` char(50) default '',
  `class_member_intro` text,
  `class_member_last_access` int(11) default NULL,
  `class_member_charge` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_post
-- ----------------------------
CREATE TABLE `tbl_class_post` (
  `class_id` int(10) unsigned NOT NULL,
  `class_post_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_post_time` int(10) unsigned NOT NULL,
  `class_post_author` int(10) unsigned NOT NULL,
  `class_post_content` text NOT NULL,
  `class_post_view_num` int(11) NOT NULL default '0',
  `class_post_tag` text,
  PRIMARY KEY  (`class_post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_privacy
-- ----------------------------
CREATE TABLE `tbl_class_privacy` (
  `class_id` int(10) unsigned NOT NULL,
  `class_join` tinyint(3) unsigned NOT NULL default '0' COMMENT '0:申请制 1:完全开放 2:只能内部邀请',
  `class_post` tinyint(3) unsigned NOT NULL default '0',
  `class_album` tinyint(3) unsigned NOT NULL default '0',
  `class_addressbook` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_reply
-- ----------------------------
CREATE TABLE `tbl_class_reply` (
  `class_reply_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_id` int(10) unsigned NOT NULL,
  `class_reply_title` char(200) NOT NULL,
  `class_reply_content` text NOT NULL,
  `class_reply_time` int(11) NOT NULL,
  PRIMARY KEY  (`class_reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_user
-- ----------------------------
CREATE TABLE `tbl_class_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_class_apply
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_apply` AS select `tbl_class_user`.`realName` AS `realName`,`tbl_class_apply`.`class_member_id` AS `class_member_id`,`tbl_class_apply`.`class_apply_content` AS `class_apply_content`,`tbl_class_apply`.`class_apply_time` AS `class_apply_time`,`tbl_class_apply`.`class_id` AS `class_id`,`tbl_class_apply`.`class_apply_id` AS `class_apply_id` from (`tbl_class_apply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_apply`.`class_member_id`)));

-- ----------------------------
-- View structure for vi_class_base
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_base` AS select `tbl_class`.`class_id` AS `class_id`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_privacy`.`class_join` AS `class_join`,`tbl_class_privacy`.`class_post` AS `class_post`,`tbl_class_privacy`.`class_album` AS `class_album`,`tbl_class_privacy`.`class_addressbook` AS `class_addressbook` from ((`tbl_class_privacy` join `tbl_class` on((`tbl_class_privacy`.`class_id` = `tbl_class`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class`.`class_charge`)));

-- ----------------------------
-- View structure for vi_class_charge
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_charge` AS select `tbl_class_user`.`realName` AS `realName`,`tbl_class`.`class_id` AS `class_id`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_year` AS `class_year` from (`tbl_class` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class`.`class_charge`)));

-- ----------------------------
-- View structure for vi_class_member
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_member` AS select `tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class`.`class_id` AS `class_id`,`tbl_class_member`.`class_member_nickname` AS `class_member_nickname`,`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`tbl_class`.`class_charge` AS `class_charge` from (`tbl_class_member` join `tbl_class` on((`tbl_class_member`.`class_id` = `tbl_class`.`class_id`)));

-- ----------------------------
-- View structure for vi_class_member_charge
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_member_charge` AS select `tbl_class_user`.`realName` AS `realName`,`tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`tbl_class_member`.`class_id` AS `class_id` from (`tbl_class_user` join `tbl_class_member` on((`tbl_class_user`.`uid` = `tbl_class_member`.`class_member_id`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_class` VALUES ('1', '国际贸易2', '外国语言文化与国际交流学院', '2002', '1208766681', '1', '我来写点东西~~~哈哈', '3');
INSERT INTO `tbl_class_member` VALUES ('1', '1', '1208766681', '0', '', null, '1208766681', '0');
INSERT INTO `tbl_class_member` VALUES ('2', '1', '1208767038', '0', '', null, null, '0');
INSERT INTO `tbl_class_member` VALUES ('3', '1', '1208768475', '0', '', null, null, '0');
INSERT INTO `tbl_class_privacy` VALUES ('1', '0', '0', '0', '0');
INSERT INTO `tbl_class_user` VALUES ('1', '林宇');
INSERT INTO `tbl_class_user` VALUES ('2', 'test');
INSERT INTO `tbl_class_user` VALUES ('3', '测试2');
