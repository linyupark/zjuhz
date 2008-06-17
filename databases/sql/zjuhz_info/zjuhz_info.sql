/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_info
Target Host: localhost
Target Database: zjuhz_info
Date: 2008-6-17 16:44:57
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_category
-- ----------------------------
CREATE TABLE `tbl_category` (
  `category_id` int(10) unsigned NOT NULL auto_increment,
  `category_icon` char(50) NOT NULL,
  `category_name` char(100) NOT NULL,
  `category_pub` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`category_id`),
  UNIQUE KEY `cate_name` (`category_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_comment
-- ----------------------------
CREATE TABLE `tbl_comment` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `entity_id` int(10) unsigned NOT NULL,
  `comment_username` char(16) NOT NULL,
  `comment_time` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_entity
-- ----------------------------
CREATE TABLE `tbl_entity` (
  `entity_id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `entity_title` char(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entity_view_num` int(11) NOT NULL default '0',
  `entity_pub_time` int(11) default '0',
  `entity_mod_time` int(11) default '0',
  `entity_content` text NOT NULL,
  `entity_tag` tinytext,
  `entity_pub` tinyint(1) NOT NULL default '0',
  `entity_top` tinyint(1) NOT NULL default '0',
  `entity_private` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`entity_id`),
  UNIQUE KEY `entity_title` (`entity_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_role
-- ----------------------------
CREATE TABLE `tbl_role` (
  `role_id` int(10) unsigned NOT NULL auto_increment,
  `role_name` char(25) NOT NULL,
  `role_nickname` char(25) NOT NULL,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
CREATE TABLE `tbl_user` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` char(50) NOT NULL,
  `realName` char(10) NOT NULL,
  `user_password` char(32) NOT NULL,
  `user_role` char(20) NOT NULL,
  `user_coin` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_entity
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_entity` AS select `tbl_entity`.`entity_id` AS `entity_id`,`tbl_entity`.`category_id` AS `category_id`,`tbl_entity`.`entity_title` AS `entity_title`,`tbl_entity`.`user_id` AS `user_id`,`tbl_entity`.`entity_view_num` AS `entity_view_num`,`tbl_entity`.`entity_pub_time` AS `entity_pub_time`,`tbl_entity`.`entity_mod_time` AS `entity_mod_time`,`tbl_entity`.`entity_content` AS `entity_content`,`tbl_entity`.`entity_tag` AS `entity_tag`,`tbl_entity`.`entity_pub` AS `entity_pub`,`tbl_entity`.`entity_top` AS `entity_top`,`tbl_user`.`user_name` AS `user_name`,`tbl_role`.`role_nickname` AS `role_nickname`,`tbl_category`.`category_icon` AS `category_icon`,`tbl_category`.`category_name` AS `category_name`,`tbl_category`.`category_pub` AS `category_pub`,`tbl_entity`.`entity_private` AS `entity_private`,`tbl_user`.`realName` AS `realName` from (((`tbl_entity` join `tbl_user` on((`tbl_user`.`user_id` = `tbl_entity`.`user_id`))) join `tbl_role` on((`tbl_role`.`role_name` = `tbl_user`.`user_role`))) join `tbl_category` on((`tbl_category`.`category_id` = `tbl_entity`.`category_id`)));

-- ----------------------------
-- Records 
-- ----------------------------
