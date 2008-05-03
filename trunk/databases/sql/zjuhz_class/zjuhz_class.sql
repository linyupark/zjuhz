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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_user
-- ----------------------------
CREATE TABLE `tbl_class_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  `sex` char(3) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_class
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class` AS select `tbl_class_privacy`.`class_join` AS `class_join`,`tbl_class_privacy`.`class_post` AS `class_post`,`tbl_class_privacy`.`class_album` AS `class_album`,`tbl_class_privacy`.`class_addressbook` AS `class_addressbook`,`tbl_class`.`class_id` AS `class_id`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class` join `tbl_class_privacy` on((`tbl_class_privacy`.`class_id` = `tbl_class`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class`.`class_charge`)));

-- ----------------------------
-- View structure for vi_class_addressbook
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_addressbook` AS select `tbl_class_addressbook`.`class_addressbook_id` AS `class_addressbook_id`,`tbl_class_addressbook`.`class_id` AS `class_id`,`tbl_class_addressbook`.`uid` AS `uid`,`tbl_class_addressbook`.`cname` AS `cname`,`tbl_class_addressbook`.`mobile` AS `mobile`,`tbl_class_addressbook`.`eMail` AS `eMail`,`tbl_class_addressbook`.`qq` AS `qq`,`tbl_class_addressbook`.`msn` AS `msn`,`tbl_class_addressbook`.`address` AS `address`,`tbl_class_addressbook`.`postcode` AS `postcode`,`tbl_class_addressbook`.`addressbook_telephone` AS `addressbook_telephone`,`tbl_class_addressbook`.`addressbook_company` AS `addressbook_company`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from (`tbl_class_addressbook` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_addressbook`.`uid`)));

-- ----------------------------
-- View structure for vi_class_apply
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_apply` AS select `tbl_class_apply`.`class_apply_id` AS `class_apply_id`,`tbl_class_apply`.`class_id` AS `class_id`,`tbl_class_apply`.`class_member_id` AS `class_member_id`,`tbl_class_apply`.`class_apply_content` AS `class_apply_content`,`tbl_class_apply`.`class_apply_time` AS `class_apply_time`,`tbl_class_user`.`realName` AS `realName`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class_apply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_apply`.`class_member_id`))) join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_apply`.`class_id`)));

-- ----------------------------
-- View structure for vi_class_member
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_member` AS select `tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class_member`.`class_id` AS `class_id`,`tbl_class_member`.`class_member_join_time` AS `class_member_join_time`,`tbl_class_member`.`class_member_status` AS `class_member_status`,`tbl_class_member`.`class_member_intro` AS `class_member_intro`,`tbl_class_member`.`class_member_last_access` AS `class_member_last_access`,`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from ((`tbl_class_member` join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_member`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_member`.`class_member_id`)));

-- ----------------------------
-- View structure for vi_class_reply
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_reply` AS select `tbl_class_reply`.`class_reply_id` AS `class_reply_id`,`tbl_class_reply`.`class_activity_id` AS `class_activity_id`,`tbl_class_reply`.`class_activity_join` AS `class_activity_join`,`tbl_class_reply`.`class_topic_id` AS `class_topic_id`,`tbl_class_reply`.`class_reply_author` AS `class_reply_author`,`tbl_class_reply`.`class_reply_title` AS `class_reply_title`,`tbl_class_reply`.`class_reply_content` AS `class_reply_content`,`tbl_class_reply`.`class_reply_time` AS `class_reply_time`,`tbl_class_user`.`realName` AS `realName`,`tbl_class_user`.`sex` AS `sex` from (`tbl_class_reply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_reply`.`class_reply_author`)));

-- ----------------------------
-- View structure for vi_class_topic
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_topic` AS select `tbl_class_topic`.`class_id` AS `class_id`,`tbl_class_topic`.`class_topic_id` AS `class_topic_id`,`tbl_class_topic`.`class_topic_author` AS `class_topic_author`,`tbl_class_topic`.`class_topic_title` AS `class_topic_title`,`tbl_class_topic`.`class_topic_content` AS `class_topic_content`,`tbl_class_topic`.`class_topic_pub_time` AS `class_topic_pub_time`,`tbl_class_topic`.`class_topic_mod_time` AS `class_topic_mod_time`,`tbl_class_topic`.`class_topic_last_reply_author` AS `class_topic_last_reply_author`,`tbl_class_topic`.`class_topic_last_reply_time` AS `class_topic_last_reply_time`,`tbl_class_topic`.`class_topic_view_num` AS `class_topic_view_num`,`tbl_class_topic`.`class_topic_reply_num` AS `class_topic_reply_num`,`tbl_class_topic`.`class_topic_up` AS `class_topic_up`,`tbl_class_topic`.`class_topic_elite` AS `class_topic_elite`,`tbl_class_topic`.`class_topic_public` AS `class_topic_public`,`tbl_class_topic`.`class_topic_tag` AS `class_topic_tag`,`u1`.`realName` AS `topicAuthor`,`u2`.`realName` AS `replyAuthor` from ((`tbl_class_topic` left join `tbl_class_user` `u1` on((`u1`.`uid` = `tbl_class_topic`.`class_topic_author`))) left join `tbl_class_user` `u2` on((`u2`.`uid` = `tbl_class_topic`.`class_topic_last_reply_author`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_class` VALUES ('1', '工商管理二班', '理学院', '2006', '1209564106', '2', '', '2');
INSERT INTO `tbl_class_addressbook` VALUES ('1', '1', '2', '林宇', null, null, null, null, null, null, null, null);
INSERT INTO `tbl_class_addressbook` VALUES ('2', '1', '3', '哈哈', null, null, null, null, null, null, null, null);
INSERT INTO `tbl_class_member` VALUES ('2', '1', '1209564106', '0', null, '1209824037', '0');
INSERT INTO `tbl_class_member` VALUES ('3', '1', '1209816457', '0', null, '1209824051', '0');
INSERT INTO `tbl_class_privacy` VALUES ('1', '0', '0', '0', '0');
INSERT INTO `tbl_class_topic` VALUES ('1', '1', '2', '时尚的真谛', '<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">走过一冬的沉寂，时尚界新一轮的流行已开始如花般绽放。从T型台上的霓裳艳影，到一个人住的小小寓所，都铺满了这一季的绚烂。前些时侯，依诺维绅在德国科隆家具展上推出的2004年的新款沙发，带着对今年时尚的崭新理解，演绎出了不同凡响的面料色彩的新概念。展览展示了时尚生活的种种场景。“设计”、“梦幻世界”和SITABLE是这次展会所提倡的主题。而依诺维绅对面料设计的大胆创新，更是为今年的时尚生活注入了更多的幻想可能。</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">&nbsp;</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">其实，人们喜欢的本就不是一成不变的生活。因为生命繁荣的根本就是推陈出新。于是，人们开始热衷于流行的种种。面对越来越多元化的流行，人们开始挑剔，对其中每一根线条，对每一种大胆的色彩组合，都一定要做出详尽的认证才能接受，时尚已经是一种越来越精细的箴言。但我们也一直对某些事情深信不移，就像钟情依诺维绅的沙发。那简单的线条下所蕴涵的技术含量，还有那些美丽得无与伦比的面料，都让我们无可挑剔。</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">&nbsp;</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">上个世纪30年代，那真是个甜美纯真与前卫的设计珠联璧合的年代。绚丽的花朵，各种眩目的颜色，更为简洁的异想天开的造型，都开始大行其道，前卫的思潮与优雅的气度幻化出了一个又一个的经典与传奇。包括了时装界的许多经典设计。今年，值得庆幸的是，那个30年代的风格又回到了我们身边，在恍若隔世的繁花面前，依诺维绅与时装界的各大品牌一起，重新演绎出了一个经典辈出的年代。</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">&nbsp;&nbsp; </p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">一张印满了花朵的沙发，带着似曾相识的妩媚与激情，让年轻与前卫都开始如花般绽放。这是依诺维绅今年所推出的新款沙发。浓郁的色彩，在那些冷调的灰色时间里穿行，带着一身的灿烂。于是，在今年的时尚界，流光异彩的不该只是那些美丽的衣裳，依诺维绅的家具设计以独特的对时尚领悟能力，正同样引领了时尚的潮流趋势。</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">&nbsp;</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">据世界权感机构预测，漂亮的花枝图案，加上惹人怜爱的粉色与绿色，以及30年代曾经流行过的种种，都会重新成为今年的时尚主流。为配合流行的走势，依诺维绅 04年推出的面料图案中还包含了许多充满激情的条纹和几何图形。与此同时，在许多大的品牌的时装发布会上，几乎同时上演了同样的一场视觉盛宴—在那里，花朵与条纹相映生辉。</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">&nbsp;</p>\r\n<p class=MsoNormal style=\\\"MARGIN: 0cm 0cm 0pt\\\">于是，在与上世纪30年代同样温煦的阳光中，人们惊喜地发现，时间的流逝与变迁，那些多年前的经典似乎并没有留下任何的印记。毋庸质疑，依诺维绅此季的崭新设计，真的让人们重新领略了那一种时尚的真谛。</p>\r\n<div class=ziyuannav id=ziyuannav style=\\\"DISPLAY: block\\\"><img alt=\\\"\\\" src=\\\"http://zjuhz/static/classes/1/images/200804301209565039281.jpg\\\" border=0 /></div>', '1209565046', null, null, null, '8', '0', '1', '0', '0', '');
INSERT INTO `tbl_class_topic` VALUES ('1', '2', '2', 'Listen Now', '<p>No Commercials and no Station IDs. Just Music!<br />\n<em>The stream is at 64k. If you need to test your internet connection speed scroll down for links.不是</em></p>\n<p><strong>Click on the “LISTEN” link to open player. </strong>After player opens you will have station options. Enable popups to open player.</p>\n<div><a onclick=\\\"\\\" finetune=\\\"\\\" ,=\\\"\\\" height=\\\"296,\\\" width=\\\"599,\\\" scrollbars=\\\"no\\\')&quot;\\\" href=\\\"javascript:void(0)\\\">LISTEN</a></div>', '1209693338', '1209694332', '2', '1209824005', '6', '0', '0', '0', '1', 'new age');
INSERT INTO `tbl_class_topic` VALUES ('1', '4', '3', '再来一个', '按时大苏打', '1209816582', null, null, null, '1', '0', '0', '0', '0', '');
INSERT INTO `tbl_class_user` VALUES ('2', '林宇', '男');
INSERT INTO `tbl_class_user` VALUES ('3', '哈哈', '男');
