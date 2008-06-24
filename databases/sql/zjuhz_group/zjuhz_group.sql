/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_group
Target Host: localhost
Target Database: zjuhz_group
Date: 2008-6-24 16:51:40
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_group
-- ----------------------------
CREATE TABLE `tbl_group` (
  `group_id` int(10) unsigned NOT NULL auto_increment COMMENT 'ç¾¤id',
  `sort_id` int(10) unsigned NOT NULL COMMENT 'ç¾¤ç±»id',
  `creater` int(10) unsigned NOT NULL COMMENT 'ç«‹å»ºäººid',
  `name` char(80) NOT NULL COMMENT 'ç¾¤åç§°',
  `url` char(80) NOT NULL COMMENT 'URLè¿žæŽ¥æ‰€ç”¨åç§°',
  `create_time` int(10) unsigned NOT NULL COMMENT 'å»ºç«‹æ—¶é—´',
  `update_time` int(10) unsigned default NULL,
  `member_num` int(11) unsigned NOT NULL default '1' COMMENT 'ç»„ç¾¤äººæ•°',
  `photo_num` int(10) unsigned NOT NULL default '0',
  `topic_num` int(10) unsigned NOT NULL default '0',
  `icon` char(32) default NULL COMMENT 'ç¾¤å›¾æ ‡æ–‡ä»¶å',
  `intro` char(200) NOT NULL COMMENT 'ç¾¤ä»‹ç»',
  `notice` text,
  `private` tinyint(1) unsigned NOT NULL default '1' COMMENT '1:å®Œå…¨å…¬å¼€;2:éœ€è¦ç”³è¯·åŠ å…¥;3:åªæœ‰è¢«é‚€è¯·åŠ å…¥',
  `tags` tinytext COMMENT 'ç¾¤ç»„æ ‡ç­¾',
  `total_click` int(10) unsigned NOT NULL default '0' COMMENT 'æ€»è®¿é—®é‡',
  `y_m_d` char(10) NOT NULL,
  `yesterday_click` int(10) unsigned NOT NULL default '0',
  `today_click` int(10) unsigned NOT NULL default '0',
  `associate` tinytext COMMENT 'gid1,gid2,å‘˜æˆæœ€è¿‘è®¿é—®çš„ç¾¤ç»„id',
  `apply` tinytext COMMENT 'è¯·æ±‚åŠ å…¥uid1,uid2',
  `ext_location` char(50) default NULL,
  `ext_trade` tinyint(2) unsigned default NULL,
  `ext_year` tinyint(4) unsigned default NULL,
  `ext_college` tinyint(2) unsigned default NULL,
  `ext_job` tinyint(2) unsigned default NULL,
  `ext_corp` char(50) default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
-- Table structure for tbl_group_link
-- ----------------------------
CREATE TABLE `tbl_group_link` (
  `link_id` int(10) unsigned NOT NULL auto_increment,
  `group_id` int(10) unsigned NOT NULL,
  `name` char(50) NOT NULL,
  `url` char(100) NOT NULL,
  `intro` char(100) NOT NULL,
  PRIMARY KEY  (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_member
-- ----------------------------
CREATE TABLE `tbl_group_member` (
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `join_time` int(10) unsigned NOT NULL,
  `last_access` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL default '0' COMMENT 'ç”¨æˆ·åœ¨å½“å‰ç¾¤ç»„çš„æ´»è·ƒåº¦',
  `role` char(7) NOT NULL default 'member',
  `event_time` int(10) unsigned default NULL,
  `event_url` tinytext,
  `event_type` tinyint(1) unsigned default NULL COMMENT '0:帖子;1:照片;2:活动...'
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
  `is_deny` tinyint(1) unsigned NOT NULL default '0' COMMENT 'å¦æ˜¯å±è”½',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_role
-- ----------------------------
CREATE TABLE `tbl_group_role` (
  `group_id` int(10) unsigned NOT NULL,
  `member` char(30) NOT NULL default 'æˆå‘˜',
  `creater` char(30) NOT NULL default 'ç»„é•¿',
  `manager` char(30) NOT NULL default 'å‰¯ç»„é•¿',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_tag
-- ----------------------------
CREATE TABLE `tbl_group_tag` (
  `tag_id` int(10) unsigned NOT NULL auto_increment,
  `sort_id` int(10) unsigned NOT NULL,
  `name` char(50) NOT NULL,
  `rate` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_group_topic
-- ----------------------------
CREATE TABLE `tbl_group_topic` (
  `topic_id` int(10) unsigned NOT NULL auto_increment,
  `group_id` int(10) unsigned NOT NULL,
  `pub_user` int(10) unsigned NOT NULL COMMENT 'å‘å¸ƒç”¨æˆ·id',
  `reply_user` int(10) unsigned default NULL COMMENT 'æœ€åŽå›žå¤ç”¨æˆ·id',
  `title` char(200) NOT NULL,
  `pub_time` int(10) unsigned NOT NULL,
  `mod_time` int(11) NOT NULL,
  `reply_time` int(10) unsigned default NULL COMMENT 'åŽæœ€å›žå¤æ—¶é—´',
  `reply_num` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  `click_num` int(11) unsigned NOT NULL default '0',
  `vote_num` tinytext COMMENT 'ç»“æžœ1,ç»“æžœ2,',
  `tags` tinytext,
  `is_vote` tinyint(1) unsigned NOT NULL default '0' COMMENT 'å¦æ˜¯ä¸ºæŠ•ç¥¨',
  `is_private` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:å¼€æ”¾,1:ç»„å†…æˆå‘˜',
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
  `group_state` tinyint(1) unsigned NOT NULL default '0' COMMENT '0:åœ¨çº¿;1:ç¦»å¼€;2:éšèº«',
  `group_invite` tinytext COMMENT 'é‚€è¯·gid1,é‚€è¯·gid2',
  `come_from` int(10) unsigned default NULL COMMENT 'æœ€è¿‘è®¿é—®çš„ç¾¤ç»„id',
  `no_group` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_group_member
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_group_member` AS select `tbl_group_member`.`group_id` AS `group_id`,`tbl_group_member`.`user_id` AS `user_id`,`tbl_group_member`.`join_time` AS `join_time`,`tbl_group_member`.`last_access` AS `last_access`,`tbl_group_member`.`active` AS `active`,`tbl_group_member`.`role` AS `role`,`tbl_group_user`.`realName` AS `realName`,`tbl_group_user`.`sex` AS `sex`,`tbl_group_user`.`group_coin` AS `group_coin`,`tbl_group_user`.`group_state` AS `group_state`,`tbl_group_user`.`group_invite` AS `group_invite`,`tbl_group_user`.`come_from` AS `come_from`,`tbl_group_user`.`no_group` AS `no_group`,`tbl_group`.`sort_id` AS `sort_id`,`tbl_group`.`creater` AS `creater`,`tbl_group`.`name` AS `name`,`tbl_group`.`url` AS `url`,`tbl_group`.`create_time` AS `create_time`,`tbl_group`.`update_time` AS `update_time`,`tbl_group`.`member_num` AS `member_num`,`tbl_group`.`photo_num` AS `photo_num`,`tbl_group`.`topic_num` AS `topic_num`,`tbl_group`.`icon` AS `icon`,`tbl_group`.`intro` AS `intro`,`tbl_group`.`private` AS `private`,`tbl_group`.`tags` AS `tags`,`tbl_group`.`total_click` AS `total_click`,`tbl_group`.`y_m_d` AS `y_m_d`,`tbl_group`.`yesterday_click` AS `yesterday_click`,`tbl_group`.`today_click` AS `today_click`,`tbl_group`.`associate` AS `associate`,`tbl_group`.`apply` AS `apply`,`tbl_group`.`ext_location` AS `ext_location`,`tbl_group`.`ext_trade` AS `ext_trade`,`tbl_group`.`ext_year` AS `ext_year`,`tbl_group`.`ext_college` AS `ext_college`,`tbl_group`.`ext_job` AS `ext_job`,`tbl_group`.`ext_corp` AS `ext_corp` from ((`tbl_group_member` left join `tbl_group` on((`tbl_group`.`group_id` = `tbl_group_member`.`group_id`))) left join `tbl_group_user` on((`tbl_group_user`.`uid` = `tbl_group_member`.`user_id`)));

-- ----------------------------
-- View structure for vi_group_topic
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_group_topic` AS select `tbl_group_topic`.`topic_id` AS `topic_id`,`tbl_group_topic`.`group_id` AS `group_id`,`tbl_group_topic`.`pub_user` AS `pub_user`,`tbl_group_topic`.`reply_user` AS `reply_user`,`tbl_group_topic`.`title` AS `title`,`tbl_group_topic`.`pub_time` AS `pub_time`,`tbl_group_topic`.`mod_time` AS `mod_time`,`tbl_group_topic`.`reply_time` AS `reply_time`,`tbl_group_topic`.`content` AS `content`,`tbl_group_topic`.`click_num` AS `click_num`,`tbl_group_topic`.`vote_num` AS `vote_num`,`tbl_group_topic`.`tags` AS `tags`,`tbl_group_topic`.`is_vote` AS `is_vote`,`tbl_group_topic`.`is_private` AS `is_private`,`tbl_group_topic`.`is_elite` AS `is_elite`,`tbl_group_topic`.`is_top` AS `is_top`,`u1`.`realName` AS `pub_user_name`,`u2`.`realName` AS `reply_user_name`,`tbl_group_topic`.`reply_num` AS `reply_num` from ((`tbl_group_topic` left join `tbl_group_user` `u1` on((`u1`.`uid` = `tbl_group_topic`.`pub_user`))) left join `tbl_group_user` `u2` on((`u2`.`uid` = `tbl_group_topic`.`reply_user`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_group` VALUES ('1', '32', '4', 'ACç±³å…°', 'nba', '1214207706', null, '2', '0', '0', null, 'çº¢è‰²æ˜¯æ¿€æƒ…çš„è¡€æ¶²ï¼Œé»‘è‰²æ˜¯ç†æ€§çš„åŒçœ¸ã€‚æ±‡èšç±³å…°é“æ†æ‹¥è¶¸ï¼Œå±•ç¤ºçº¢é»‘çƒè¿·é£Žé‡‡!', '\nä½ å¥½ï¼Œè¿™é‡Œæ˜¯ACç±³å…°ç¾¤ç»„\n\n\n<img src=\\\"/static/groups/1/images/200806241214289123178.gif\\\" alt=\\\"\\\" border=\\\"0\\\" />', '1', 'è¶³çƒ ACç±³å…°', '9', '2008-06-24', '0', '9', null, null, null, null, null, null, null, null);
INSERT INTO `tbl_group` VALUES ('2', '52', '6', 'æµ‹è¯•ç¾¤ç»„', 'text', '1214275948', null, '1', '0', '0', null, 'æµ‹è¯•ç¾¤ç»„æµ‹è¯•ç¾¤ç»„æµ‹è¯•ç¾¤ç»„æµ‹è¯•ç¾¤ç»„æµ‹è¯•ç¾¤ç»„', null, '1', 'æµ‹è¯•ç¾¤ç»„', '0', '2008-06-24', '0', '0', null, null, null, null, null, null, null, null);
INSERT INTO `tbl_group` VALUES ('3', '32', '8', 'æ¬§æ´²æ¯', 'er', '1214285711', null, '1', '0', '0', null, 'æ¬§æ´²æ¯æ¬§æ´²æ¯æ¬§æ´²æ¯æ¬§æ´²æ¯æ¬§æ´²æ¯æ¬§æ´²æ¯æ¬§æ´²æ¯', null, '1', 'æ¬§æ´²æ¯', '1', '2008-06-24', '0', '1', null, null, null, null, null, null, null, null);
INSERT INTO `tbl_group_member` VALUES ('1', '4', '1214207706', '1214207706', '0', 'creater', null, null, null);
INSERT INTO `tbl_group_member` VALUES ('2', '6', '1214275949', '1214275949', '0', 'creater', null, null, null);
INSERT INTO `tbl_group_member` VALUES ('1', '8', '1214278025', '1214278025', '0', 'member', null, null, null);
INSERT INTO `tbl_group_member` VALUES ('3', '8', '1214285711', '1214285711', '0', 'creater', null, null, null);
INSERT INTO `tbl_group_role` VALUES ('1', 'æˆå‘˜', 'ç»„é•¿', 'å‰¯ç»„é•¿');
INSERT INTO `tbl_group_role` VALUES ('2', 'æˆå‘˜', 'ç»„é•¿', 'å‰¯ç»„é•¿');
INSERT INTO `tbl_group_role` VALUES ('3', 'æˆå‘˜', 'ç»„é•¿', 'å‰¯ç»„é•¿');
INSERT INTO `tbl_group_tag` VALUES ('1', '32', 'nba', '2');
INSERT INTO `tbl_group_tag` VALUES ('2', '52', 'æµ‹è¯•ç¾¤ç»„', '1');
INSERT INTO `tbl_group_tag` VALUES ('3', '32', 'ç¯®çƒ', '1');
INSERT INTO `tbl_group_tag` VALUES ('4', '32', 'è¶³çƒ', '1');
INSERT INTO `tbl_group_tag` VALUES ('5', '32', 'æ¬§æ´²æ¯', '1');
INSERT INTO `tbl_group_user` VALUES ('4', 'æž—å®‡', 'ç”·', '0', '0', null, null, '0');
INSERT INTO `tbl_group_user` VALUES ('6', 'æ–¹æ¢“æ¬£', 'ç”·', '0', '0', null, null, '0');
INSERT INTO `tbl_group_user` VALUES ('2', 'é™ˆé¸¿', 'ç”·', '100', '0', '2,1', null, '1');
INSERT INTO `tbl_group_user` VALUES ('3', 'çŽ‹é›¨æ—»', 'ç”·', '100', '0', '2,1', null, '1');
INSERT INTO `tbl_group_user` VALUES ('5', 'å¼ å¨‡å¨‡', 'å¥³', '100', '0', '2,1', null, '1');
INSERT INTO `tbl_group_user` VALUES ('7', 'èµµçˆ±æ¡”', 'å¥³', '100', '0', '2,1', null, '1');
INSERT INTO `tbl_group_user` VALUES ('8', 'è”¡æµ·ä¸œ', 'ç”·', '0', '0', null, null, '0');
INSERT INTO `tbl_group_user` VALUES ('9', 'ä¾¯é‘«', 'ç”·', '100', '0', null, null, '1');
