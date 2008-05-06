/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_class
Target Host: localhost
Target Database: zjuhz_class
Date: 2008-5-3 22:15:57
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
  `class_create_time` int(11) unsigned NOT NULL,
  `class_charge` int(10) unsigned NOT NULL,
  `class_notice` text,
  `class_member_num` smallint(6) unsigned NOT NULL default '1',
  PRIMARY KEY  (`class_id`),
  UNIQUE KEY `className` (`class_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_activity
-- ----------------------------
CREATE TABLE `tbl_class_activity` (
  `class_activity_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_id` int(10) unsigned NOT NULL,
  `class_activity_title` char(150) NOT NULL,
  `class_activity_type` char(30) NOT NULL,
  `class_activity_status` tinyint(1) unsigned NOT NULL COMMENT '0:商定,1:确定',
  `class_activity_time` int(10) unsigned default NULL,
  `class_activity_locus` char(200) NOT NULL,
  `class_activity_spending` smallint(5) unsigned default NULL,
  `class_activity_memo` text,
  `class_activity_close` int(10) unsigned default NULL,
  PRIMARY KEY  (`class_activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_addressbook
-- ----------------------------
CREATE TABLE `tbl_class_addressbook` (
  `class_addressbook_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_id` int(11) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `cname` char(10) NOT NULL,
  `mobile` char(15) default NULL,
  `eMail` char(50) default NULL,
  `qq` char(15) default NULL,
  `msn` char(50) default NULL,
  `address` char(80) default NULL,
  `postcode` char(6) default NULL,
  `addressbook_telephone` char(20) default NULL,
  `addressbook_company` char(120) default NULL,
  PRIMARY KEY  (`class_addressbook_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_member
-- ----------------------------
CREATE TABLE `tbl_class_member` (
  `class_member_id` int(10) unsigned NOT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `class_member_join_time` int(10) unsigned NOT NULL,
  `class_member_status` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:正常 1:忙碌 2:离开',
  `class_member_intro` text,
  `class_member_last_access` int(11) unsigned default NULL,
  `class_member_charge` tinyint(1) unsigned NOT NULL default '0'
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
  `class_activity_id` int(10) unsigned NOT NULL default '0',
  `class_activity_join` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:没决定,1:参加',
  `class_topic_id` int(10) unsigned NOT NULL default '0',
  `class_reply_author` int(10) unsigned NOT NULL,
  `class_reply_title` char(200) NOT NULL,
  `class_reply_content` text NOT NULL,
  `class_reply_time` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`class_reply_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_topic
-- ----------------------------
CREATE TABLE `tbl_class_topic` (
  `class_id` int(10) unsigned NOT NULL,
  `class_topic_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_topic_author` int(10) unsigned NOT NULL,
  `class_topic_title` char(100) NOT NULL,
  `class_topic_content` text NOT NULL,
  `class_topic_pub_time` int(10) unsigned NOT NULL,
  `class_topic_mod_time` int(11) default NULL,
  `class_topic_last_reply_author` int(11) default NULL,
  `class_topic_last_reply_time` int(11) unsigned default NULL,
  `class_topic_view_num` int(11) unsigned NOT NULL default '0',
  `class_topic_reply_num` int(10) unsigned NOT NULL default '0',
  `class_topic_up` tinyint(1) unsigned NOT NULL default '0',
  `class_topic_elite` tinyint(1) unsigned NOT NULL default '0',
  `class_topic_public` tinyint(1) NOT NULL default '0' COMMENT '0:只对班级成员;1:公开',
  `class_topic_tag` tinytext,
  PRIMARY KEY  (`class_topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_user
-- ----------------------------
CREATE TABLE `tbl_class_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  `sex` char(3) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_class
-- ----------------------------
CREATE VIEW `vi_class` AS select `tbl_class_privacy`.`class_join` AS `class_join`,`tbl_class_privacy`.`class_post` AS `class_post`,`tbl_class_privacy`.`class_album` AS `class_album`,`tbl_class_privacy`.`class_addressbook` AS `class_addressbook`,`tbl_class`.`class_id` AS `class_id`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class` join `tbl_class_privacy` on((`tbl_class_privacy`.`class_id` = `tbl_class`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class`.`class_charge`)));

-- ----------------------------
-- View structure for vi_class_addressbook
-- ----------------------------
CREATE VIEW `vi_class_addressbook` AS select `tbl_class_addressbook`.`class_addressbook_id` AS `class_addressbook_id`,`tbl_class_addressbook`.`class_id` AS `class_id`,`tbl_class_addressbook`.`uid` AS `uid`,`tbl_class_addressbook`.`cname` AS `cname`,`tbl_class_addressbook`.`mobile` AS `mobile`,`tbl_class_addressbook`.`eMail` AS `eMail`,`tbl_class_addressbook`.`qq` AS `qq`,`tbl_class_addressbook`.`msn` AS `msn`,`tbl_class_addressbook`.`address` AS `address`,`tbl_class_addressbook`.`postcode` AS `postcode`,`tbl_class_addressbook`.`addressbook_telephone` AS `addressbook_telephone`,`tbl_class_addressbook`.`addressbook_company` AS `addressbook_company`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from (`tbl_class_addressbook` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_addressbook`.`uid`)));

-- ----------------------------
-- View structure for vi_class_apply
-- ----------------------------
CREATE VIEW `vi_class_apply` AS select `tbl_class_apply`.`class_apply_id` AS `class_apply_id`,`tbl_class_apply`.`class_id` AS `class_id`,`tbl_class_apply`.`class_member_id` AS `class_member_id`,`tbl_class_apply`.`class_apply_content` AS `class_apply_content`,`tbl_class_apply`.`class_apply_time` AS `class_apply_time`,`tbl_class_user`.`realName` AS `realName`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class_apply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_apply`.`class_member_id`))) join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_apply`.`class_id`)));

-- ----------------------------
-- View structure for vi_class_member
-- ----------------------------
CREATE VIEW `vi_class_member` AS select `tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class_member`.`class_id` AS `class_id`,`tbl_class_member`.`class_member_join_time` AS `class_member_join_time`,`tbl_class_member`.`class_member_status` AS `class_member_status`,`tbl_class_member`.`class_member_intro` AS `class_member_intro`,`tbl_class_member`.`class_member_last_access` AS `class_member_last_access`,`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class_member` join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_member`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_member`.`class_member_id`)));

-- ----------------------------
-- View structure for vi_class_reply
-- ----------------------------
CREATE VIEW `vi_class_reply` AS select `tbl_class_reply`.`class_reply_id` AS `class_reply_id`,`tbl_class_reply`.`class_activity_id` AS `class_activity_id`,`tbl_class_reply`.`class_activity_join` AS `class_activity_join`,`tbl_class_reply`.`class_topic_id` AS `class_topic_id`,`tbl_class_reply`.`class_reply_author` AS `class_reply_author`,`tbl_class_reply`.`class_reply_title` AS `class_reply_title`,`tbl_class_reply`.`class_reply_content` AS `class_reply_content`,`tbl_class_reply`.`class_reply_time` AS `class_reply_time`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from (`tbl_class_reply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_reply`.`class_reply_author`)));

-- ----------------------------
-- View structure for vi_class_topic
-- ----------------------------
CREATE VIEW `vi_class_topic` AS select `tbl_class_topic`.`class_id` AS `class_id`,`tbl_class_topic`.`class_topic_id` AS `class_topic_id`,`tbl_class_topic`.`class_topic_author` AS `class_topic_author`,`tbl_class_topic`.`class_topic_title` AS `class_topic_title`,`tbl_class_topic`.`class_topic_content` AS `class_topic_content`,`tbl_class_topic`.`class_topic_pub_time` AS `class_topic_pub_time`,`tbl_class_topic`.`class_topic_mod_time` AS `class_topic_mod_time`,`tbl_class_topic`.`class_topic_last_reply_author` AS `class_topic_last_reply_author`,`tbl_class_topic`.`class_topic_last_reply_time` AS `class_topic_last_reply_time`,`tbl_class_topic`.`class_topic_view_num` AS `class_topic_view_num`,`tbl_class_topic`.`class_topic_reply_num` AS `class_topic_reply_num`,`tbl_class_topic`.`class_topic_up` AS `class_topic_up`,`tbl_class_topic`.`class_topic_elite` AS `class_topic_elite`,`tbl_class_topic`.`class_topic_public` AS `class_topic_public`,`tbl_class_topic`.`class_topic_tag` AS `class_topic_tag`,`u1`.`realName` AS `topicAuthor`,`u2`.`realName` AS `replyAuthor` from ((`tbl_class_topic` left join `tbl_class_user` `u1` on((`u1`.`uid` = `tbl_class_topic`.`class_topic_author`))) left join `tbl_class_user` `u2` on((`u2`.`uid` = `tbl_class_topic`.`class_topic_last_reply_author`)));

