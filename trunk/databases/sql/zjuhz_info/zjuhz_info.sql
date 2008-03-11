/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_info
Target Host: localhost
Target Database: zjuhz_info
Date: 2008-3-11 17:08:44
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_category
-- ----------------------------
CREATE TABLE `tbl_category` (
  `category_id` int(10) unsigned NOT NULL auto_increment,
  `category_name` char(100) NOT NULL,
  `parent_id` int(11) default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_comment
-- ----------------------------
CREATE TABLE `tbl_comment` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `comment_user_id` int(11) NOT NULL,
  `comment_time` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_entity
-- ----------------------------
CREATE TABLE `tbl_entity` (
  `entity_id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `entity_title` char(100) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `entity_view_num` int(11) NOT NULL default '0',
  `entity_pub_time` int(11) default '0',
  `entity_mod_time` int(11) default '0',
  PRIMARY KEY  (`entity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
CREATE TABLE `tbl_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` char(50) NOT NULL,
  `user_password` char(32) NOT NULL,
  `user_role` char(20) NOT NULL,
  `user_power` text,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_category` VALUES ('1', 'c1', null);
INSERT INTO `tbl_category` VALUES ('2', 'c2', null);
INSERT INTO `tbl_user` VALUES ('1', 'admin', '4297f44b13955235245b2497399d7a93', 'admin', null);
INSERT INTO `tbl_user` VALUES ('2', 'test_editor', '4297f44b13955235245b2497399d7a93', 'editor', '1|2');
