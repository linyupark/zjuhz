/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_group
Target Host: localhost
Target Database: zjuhz_group
Date: 2008-6-17 16:44:22
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_group
-- ----------------------------
CREATE TABLE `tbl_group` (
  `group_id` int(10) unsigned NOT NULL auto_increment COMMENT '群id',
  `sort_id` int(10) unsigned NOT NULL COMMENT '群类id',
  `master` int(10) unsigned NOT NULL COMMENT '立建人id',
  `name` char(80) NOT NULL COMMENT '群名称',
  `url` char(80) NOT NULL COMMENT 'URL连接所用名称',
  `create_time` int(10) unsigned NOT NULL COMMENT '建立时间',
  `member_num` int(11) NOT NULL COMMENT '组群人数',
  `photo_num` int(10) unsigned NOT NULL default '0',
  `topic_num` int(10) unsigned NOT NULL default '0',
  `icon` char(32) default NULL COMMENT '群图标文件名',
  `intro` text COMMENT '群介绍',
  `private` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:完全公开;1:需要申请加入;2:只有被邀请加入',
  `tags` tinytext COMMENT '群组标签',
  `total_click` int(10) unsigned NOT NULL default '0' COMMENT '总访问量',
  `today_click` char(80) default NULL,
  `associate` tinytext COMMENT 'gid1,gid2,员成最近访问的群组id',
  `apply` tinytext COMMENT '请求加入uid1,uid2',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_album
-- ----------------------------
CREATE TABLE `tbl_group_album` (
  `album_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `title` char(100) NOT NULL,
  `intro` tinytext NOT NULL,
  `file` char(32) NOT NULL,
  `pubtime` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`album_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_member
-- ----------------------------
CREATE TABLE `tbl_group_member` (
  `gid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `join_time` int(10) unsigned NOT NULL,
  `last_access` int(10) unsigned NOT NULL,
  `is_manager` tinyint(3) unsigned NOT NULL default '0',
  `active` int(10) unsigned NOT NULL COMMENT '用户在当前群组的活跃度'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_reply
-- ----------------------------
CREATE TABLE `tbl_group_reply` (
  `reply_id` int(10) unsigned NOT NULL auto_increment,
  `topic_id` int(10) unsigned NOT NULL default '0',
  `album_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` char(100) default NULL,
  `content` text NOT NULL,
  `reply_time` int(11) NOT NULL,
  `is_deny` tinyint(1) unsigned NOT NULL default '0' COMMENT '否是屏蔽',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_sort
-- ----------------------------
CREATE TABLE `tbl_group_sort` (
  `sort_id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL,
  `name` char(50) NOT NULL COMMENT '类名',
  `child_num` int(10) unsigned NOT NULL default '0' COMMENT '有多少子类群',
  PRIMARY KEY  (`sort_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_topic
-- ----------------------------
CREATE TABLE `tbl_group_topic` (
  `topic_id` int(10) unsigned NOT NULL auto_increment,
  `group_id` int(10) unsigned NOT NULL,
  `pub_user` int(10) unsigned NOT NULL COMMENT '发布用户id',
  `reply_user` int(10) unsigned default NULL COMMENT '最后回复用户id',
  `title` char(200) NOT NULL,
  `pub_time` int(10) unsigned NOT NULL,
  `mod_time` int(11) NOT NULL,
  `reply_time` int(10) unsigned default NULL COMMENT '后最回复时间',
  `content` text NOT NULL,
  `click_num` int(11) unsigned NOT NULL default '0',
  `vote_num` tinytext COMMENT '结果1,结果2,',
  `tags` tinytext,
  `is_vote` tinyint(1) unsigned NOT NULL default '0' COMMENT '否是为投票',
  `is_private` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:开放,1:组内成员',
  `is_elite` tinyint(1) unsigned NOT NULL default '0',
  `is_top` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_user
-- ----------------------------
CREATE TABLE `tbl_group_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  `sex` char(3) NOT NULL,
  `group_coin` int(10) unsigned NOT NULL default '100',
  `group_state` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:在线;1:离开;2:隐身',
  `group_invite` tinytext COMMENT '邀请gid1,邀请gid2',
  `come_from` int(10) unsigned default NULL COMMENT '最近访问的群组id',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
