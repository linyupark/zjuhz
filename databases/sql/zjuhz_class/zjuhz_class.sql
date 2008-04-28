/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_class
Target Host: localhost
Target Database: zjuhz_class
Date: 2008-4-28 17:19:50
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
  `postcode` tinytext,
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
  `class_reply_auhor` int(10) unsigned NOT NULL,
  `class_reply_title` char(200) NOT NULL,
  `class_reply_content` text NOT NULL,
  `class_reply_time` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`class_reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `class_topic_last_reply` int(11) unsigned default NULL,
  `class_topic_view_num` int(11) unsigned NOT NULL default '0',
  `class_topic_reply_num` int(10) unsigned NOT NULL default '0',
  `class_topic_up` tinyint(1) unsigned NOT NULL default '0',
  `class_topic_elite` tinyint(1) unsigned NOT NULL default '0',
  `class_topic_public` tinyint(1) NOT NULL default '0' COMMENT '0:只对班级成员;1:公开',
  `class_topic_tag` tinytext,
  PRIMARY KEY  (`class_topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_user
-- ----------------------------
CREATE TABLE `tbl_class_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  `sex` char(3) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_class
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class` AS select `tbl_class_privacy`.`class_join` AS `class_join`,`tbl_class_privacy`.`class_post` AS `class_post`,`tbl_class_privacy`.`class_album` AS `class_album`,`tbl_class_privacy`.`class_addressbook` AS `class_addressbook`,`tbl_class`.`class_id` AS `class_id`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName` from ((`tbl_class` join `tbl_class_privacy` on((`tbl_class_privacy`.`class_id` = `tbl_class`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class`.`class_charge`)));

-- ----------------------------
-- View structure for vi_class_apply
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_apply` AS select `tbl_class_apply`.`class_apply_id` AS `class_apply_id`,`tbl_class_apply`.`class_id` AS `class_id`,`tbl_class_apply`.`class_member_id` AS `class_member_id`,`tbl_class_apply`.`class_apply_content` AS `class_apply_content`,`tbl_class_apply`.`class_apply_time` AS `class_apply_time`,`tbl_class_user`.`realName` AS `realName`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num` from ((`tbl_class_apply` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_apply`.`class_member_id`))) join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_apply`.`class_id`)));

-- ----------------------------
-- View structure for vi_class_member
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_member` AS select `tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class_member`.`class_id` AS `class_id`,`tbl_class_member`.`class_member_join_time` AS `class_member_join_time`,`tbl_class_member`.`class_member_status` AS `class_member_status`,`zjuhz_class`.`tbl_class_member`.`class_member_nickname` AS `class_member_nickname`,`zjuhz_class`.`tbl_class_member`.`class_member_intro` AS `class_member_intro`,`zjuhz_class`.`tbl_class_member`.`class_member_last_access` AS `class_member_last_access`,`zjuhz_class`.`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`zjuhz_class`.`tbl_class`.`class_name` AS `class_name`,`zjuhz_class`.`tbl_class`.`class_college` AS `class_college`,`zjuhz_class`.`tbl_class`.`class_year` AS `class_year`,`zjuhz_class`.`tbl_class`.`class_create_time` AS `class_create_time`,`zjuhz_class`.`tbl_class`.`class_charge` AS `class_charge`,`zjuhz_class`.`tbl_class`.`class_notice` AS `class_notice`,`zjuhz_class`.`tbl_class`.`class_member_num` AS `class_member_num`,`zjuhz_class`.`tbl_class_user`.`realName` AS `realName` from ((`tbl_class_member` join `tbl_class` on((`zjuhz_class`.`tbl_class`.`class_id` = `zjuhz_class`.`tbl_class_member`.`class_id`))) join `tbl_class_user` on((`zjuhz_class`.`tbl_class_user`.`uid` = `zjuhz_class`.`tbl_class_member`.`class_member_id`)));

-- ----------------------------
-- View structure for vi_class_topic
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_topic` AS select `tbl_class_user`.`realName` AS `realName`,`tbl_class_topic`.`class_id` AS `class_id`,`tbl_class_topic`.`class_topic_id` AS `class_topic_id`,`tbl_class_topic`.`class_topic_author` AS `class_topic_author`,`tbl_class_topic`.`class_topic_title` AS `class_topic_title`,`tbl_class_topic`.`class_topic_content` AS `class_topic_content`,`tbl_class_topic`.`class_topic_pub_time` AS `class_topic_pub_time`,`tbl_class_topic`.`class_topic_mod_time` AS `class_topic_mod_time`,`tbl_class_topic`.`class_topic_reply_num` AS `class_topic_reply_num`,`tbl_class_topic`.`class_topic_up` AS `class_topic_up`,`tbl_class_topic`.`class_topic_public` AS `class_topic_public`,`tbl_class_topic`.`class_topic_tag` AS `class_topic_tag`,`tbl_class_topic`.`class_topic_last_reply` AS `class_topic_last_reply`,`tbl_class_topic`.`class_topic_view_num` AS `class_topic_view_num`,`tbl_class_topic`.`class_topic_elite` AS `class_topic_elite` from (`tbl_class_topic` join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_topic`.`class_topic_author`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_class` VALUES ('1', '06工商管理二班', '理学院', '2006', '1209086301', '1', 'asdasdasdasd', '2');
INSERT INTO `tbl_class_addressbook` VALUES ('1', '1', '1', '林啊', null, null, null, null, null, null);
INSERT INTO `tbl_class_addressbook` VALUES ('2', '1', '2', '测试1', null, null, null, null, null, null);
INSERT INTO `tbl_class_member` VALUES ('1', '1', '1209086301', '0', null, '1209374224', '0');
INSERT INTO `tbl_class_member` VALUES ('2', '1', '1209366485', '0', null, '1209374204', '1');
INSERT INTO `tbl_class_privacy` VALUES ('1', '0', '0', '0', '0');
INSERT INTO `tbl_class_topic` VALUES ('1', '1', '1', '我的十年-第二章 童年', '<p><strong><font color=\\\"#ff0000\\\">2.童年</font></strong></p><p>&nbsp;</p><p><font color=\\\"#ff00ff\\\">童年是青枣儿红色的梦；是小夜曲空灵的旋律；是古希腊瑰丽的史诗；是年迈而慈祥的老奶奶讲了一遍又一遍的关于小山村与我的故事……</font></p><p>&nbsp;</p><p>&nbsp;</p><p>终于，她的幻影消失在城市的雨幕中。我的泪又来了，我心中升起一片无边的空虚与寂寞。前方漫漫的征途只剩下我独自上路，未来的艰辛从此一个人担当。以后只有那年迈而慈祥的奶奶会疼爱我了。此时耳边仿佛又响起了奶奶那讲了一遍又一遍的关于小山村的故事。</p><p>&nbsp;</p><p>小\n时候，记得在每个夜幕降临的夏天晚上，奶奶总要领着我们到院子里纳凉。我总喜欢躺在竹床上，抬眼看夜空中的星星，听着奶奶讲故事，然后悄悄地进入梦乡……\n奶奶那双长满老茧的手总是在我的头上轻轻地抚摸着，抚摸着，她慈祥地看着她的小孙子，静静地回想着往事，然后在她那饱经风霜的脸上露出幸福的微笑……</p><p>&nbsp;</p><p>此时，那幸福的微笑在眼前浮现了，那熟悉而苍老的声音在耳边盘旋着，仿佛是穿越时空的电波，激活了我那尘封多年的记忆。童年往事像漂泊多年的小船，纷纷涌进记忆的港湾。</p><p>&nbsp;</p><p>其中有一只灰褐色的小船，缓缓地驶入我的脑海中。船上载着奶奶讲的关于故乡的传说。</p><p>&nbsp;</p><p>“咱\n们温家的祖先来自一个很遥远的地方，叫彭城，咱们家正门的那块匾上写着‘彭城衍派’，就是这个道理。据说当年彭城发生饥荒，咱们的祖先活不下去了，就携老\n扶幼，不辞辛劳，跋山涉水，走了四十九天，又走了四十九夜，但还是没有找到合适的地方，眼看全家老小就要饿死了。这时候，妈祖出现了，她给咱们的祖先指明\n了一条路。于是咱们的祖先按照妈祖的指点，又走了七天七夜，终于找到一个风水宝地。这个地方山清水秀，物产丰饶，因为山上开满芙蓉，咱们的祖先就给它取名\n叫芙蓉镇。咱们的祖先为了纪念指点迷津的妈祖菩萨，修建了妈祖庙，每年都要举行祭祀活动。孩子，你一定要信奉妈祖，菩萨才会保佑你……”</p><p>&nbsp;</p><p>我\n又想起了小时候每逢到了祭祀妈祖的时候，家里总要摆上三牲供奉菩萨。村里年年有社戏，由一家拿大头，其余各户添油。演出的剧目都是村里老人喜欢的高甲戏，\n以丑角最见功底。此外，村里还要举行“游景”活动，所谓“游景”，就是抬着菩萨，绕着村子游行，各家各户都要在门口摆上三牲“恭候”大驾，而我们小孩子或\n扛着旌旗，或敲着锣鼓，或吹着铜号，加入“游景”的队伍，以祈求菩萨的庇佑。游行队伍所到之处，都有免费的点心，村民在这时候表现得最慷慨、最虔诚。听奶\n奶说，“游景”是为了纪念祖先，因此游行所走的路线也有一定的讲究，据说是根据祖宗南迁的路线制定的。</p><p>&nbsp;</p><p>这是奶奶所知的最古老的故事，但对于一个好奇的孩子来说，总是渴望知道更多，因此每当奶奶讲完这一段，我总会催促着她讲下一段，于是奶奶清了清嗓子，又讲了一段故事。</p><p>&nbsp;</p><p>“我\n所知道的关于咱家的故事最早只能到你曾祖父了，听你曾祖母说，你曾祖父排名老五，是最小的一个孩子。因此他几乎没有得到任何遗产，快三十了才娶了你曾祖\n母，后来实在生活不下去了，不得不漂洋过海，到吕宋谋生去了。他在那又娶了一个番婆，从此以后就不管这边的家人了。他四十多岁的时候就暴病身亡了。唉！可\n怜你曾祖母和你爷爷孤儿寡母的，受尽了委屈。”奶奶说到这轻轻地叹了一口气，她脸上的皱纹更多了。</p><p>&nbsp;</p><p>“那曾祖父死了以后，太太和爷爷怎么办呢？”我睁大眼睛关切地问。</p><p>&nbsp;</p><p>“是啊，你想爷爷当时有多难啊。他上了两年私塾就出去当船工了，你曾祖母是个缠了脚的女人，什么事情都干不了。你爷爷内要做家务，外要当船工，苦啊！”奶奶顿了顿接着说，“更可恨的是他偏偏又遇上了歹人。”</p><p>&nbsp;</p><p>“什么是歹人？他怎么了？”我好奇地问。</p><p>&nbsp;</p><p>“歹\n人就是坏人，你静静听我讲就明白了。”奶奶的表情变得十分严肃，她接着往下讲，“事情是这样的，有一次你爷爷已经先应允给别人渡船了，这时，那个歹人，就\n是当时村里的保长，非得要你爷爷渡他的不可。你爷爷为了信守诺言当然没有答应。那厮却怀恨在心，趁清早你爷爷一个人在河边的空当，揪住他往死里打，你爷爷\n当时只有十七岁，被他打得只剩下一口气了，后来就落下了病根，几十年来没治好，一逢阴雨天气就浑身疼痛，动弹不得。”</p><p>&nbsp;</p><p>“那厮也太可恶了，要是当时我在场，我一定要狠狠地揍他一顿！”我愤愤不平的议论简直就像唐吉诃德。</p><p>&nbsp;</p><p>“孩\n子，这种事奶奶见多了，那时候有钱有势的人拿杀人都不当回事，更何况打伤人呢。好在善有善报，恶有恶报，歹人终于得到报应……”接着奶奶接着谈往日的那段\n苦难史，“你爷爷和我这种苦头吃多了。你们都不敢想象以前咱们家有多穷，你爷爷和我两个人上要养你曾祖母，下要养五个孩子。全家人挤在一间破房子里，吃不\n饱，穿不暖，还要忍气吞声地任凭人家欺负。当时咱们家是缺粮户，有一次，你伯父去领粮食。发粮的人一脚将他的筐踢到路边的臭水沟里，还破口大骂：‘缺粮户\n居然还敢来领粮食！’”奶奶说到这，掏出手帕擦眼泪。</p><p>&nbsp;</p><p>我们听到这都呆了，不知道说什么好，我们那里知道前辈人的命运居然如此之惨。平时喝麦糊的时候还嫌不好吃，可是对于奶奶他们来说，能吃上麦糊已经是当时的人最大的口福了。</p><p>“孩子，奶奶给你讲这些苦难史，就是希望你们知道今天的幸福来之不易，你们一定要出人头地，不再受人欺负……”奶奶说完就陷入沉思，也许她正想着她的小孙子光宗耀祖的那一天的情景。</p><p>然\n而我那慈祥的老奶奶怎么会知道，此时她最疼爱的小孙子正坐在一辆乌烟瘴气的车上，独自忍受着羁旅的寂寞与颠簸之苦。她怎么会知道，多年以前她曾讲过的故事\n此时在她最疼爱的小孙子的脑海中一遍又一遍的回响着。她怎么会知道，离乡多年的小孙子就要回到她身边了。没有功成名就的志得意满，没有落叶归根的恬静安\n然，只有无边的空虚与失落。</p><p>想到这，我的心里万分惆怅。往事不堪回首，童年的美好时光竟这样一去不复返，只在记忆深处留下残存的片段。然而\n这点点的记忆对于饱受羁旅的寂寞与颠簸之苦的我来说，不啻于雪中送炭，它们就像点点的寒星，指引着黑暗中的人们，指引着我这迷途的羔羊，使我暂时沉浸于对\n童年的美好回忆中，使我暂时忘却烦恼，忘却了哀愁。</p><p>&nbsp;</p><p>童年是青枣儿红色的梦；是小夜曲空灵的旋律；是古希腊瑰丽的史诗。我的\n童年也曾像星星一般自由：我和小伙伴们手拉着手儿，一路欢歌笑语，在草丛里捉蛐蛐，到小河边摸鱼儿。青青的草，艳艳的花，浅浅的河，清清的水，还有翩翩起\n舞的蝴蝶，加上悠然自得的游鱼……爽朗的笑声，天真的笑脸，深深地烙在我心中的小伙伴的形象……这一幅色彩斑斓的画图在我眼前重现了。我努力地回忆，尽情\n享受这一幸福时刻。竭力想留住他们。</p><p>&nbsp;</p>但他们在我面前一晃，又如海市蜃楼一样消失得无影无踪。我的那些小伙伴都到哪里去了，\n他们是否和我一样，有着无尽的忧愁和烦恼？抑或，他们仍然像童年一般快乐？抑或，他们已被生活的压得透不过气来？六年来，我失去了太多太多……儿时的伙伴\n也许已经面目全非——“相见不相识”了，而曾经和奶奶一样慈爱的爷爷永远地安睡在长满松柏的小山包上，唯有坟头的杂草在夕阳的余辉中随风招摇，慰藉着地下\n安息的人们。', '1209344454', null, null, '0', '0', '0', '1', '0', '十年,童年');
INSERT INTO `tbl_class_topic` VALUES ('1', '2', '1', '创业——我们需要什么？', '<p>前段时间，网上流行别针换别墅的故事。这是一个很经典的创业故事。不管故事本身真假如何，至少他给我们传递了这样一个信息：创业，真的很有意思！</p><p>创业，对于大学生来说，已经不是一个陌生的话题。如今的就业形势也不太乐观。那么为什么那么多的大学生选择打工而不是创业呢？谈到这里，我们不得不探讨创业的几个重要因素？</p><p>谁适合创业？什么时候创业？创什么业，创业需要什么？</p><p>谁适合创业呢？任何人，只要你有创业的想法。</p><p>什么时候创业呢？有的人认为先有工作经验以后再创业，有的人则认为大学毕业甚至没有毕业都是创业的好时机，典型的例子就是比尔.盖茨。</p><p>创什么业？别针换别墅，这可能是一个好的命题。</p><p>最后，创业需要什么？知识，财富，人脉，激情，信心……</p><p>还有呢？</p><p>朋友都来谈一谈吧！</p>', '1209346614', null, null, '0', '0', '0', '0', '0', '创业');
INSERT INTO `tbl_class_user` VALUES ('1', '林啊', '男');
INSERT INTO `tbl_class_user` VALUES ('2', '测试1', '男');
