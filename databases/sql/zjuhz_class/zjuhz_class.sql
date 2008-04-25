/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_class
Target Host: localhost
Target Database: zjuhz_class
Date: 2008-4-25 17:14:55
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
-- Table structure for tbl_class_apply
-- ----------------------------
CREATE TABLE `tbl_class_apply` (
  `class_apply_id` bigint(20) unsigned NOT NULL auto_increment,
  `class_id` int(10) unsigned NOT NULL,
  `class_member_id` int(10) unsigned NOT NULL,
  `class_apply_content` tinytext,
  `class_apply_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`class_apply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `class_topic_reply_num` int(10) unsigned NOT NULL default '0',
  `class_topic_up` tinyint(1) unsigned NOT NULL default '0',
  `class_topic_public` tinyint(1) NOT NULL default '0' COMMENT '0:只对班级成员;1:公开',
  `class_topic_tag` tinytext,
  PRIMARY KEY  (`class_topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_class_user
-- ----------------------------
CREATE TABLE `tbl_class_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `realName` char(16) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_class_member` AS select `tbl_class_member`.`class_member_id` AS `class_member_id`,`tbl_class_member`.`class_id` AS `class_id`,`tbl_class_member`.`class_member_join_time` AS `class_member_join_time`,`tbl_class_member`.`class_member_status` AS `class_member_status`,`tbl_class_member`.`class_member_nickname` AS `class_member_nickname`,`tbl_class_member`.`class_member_intro` AS `class_member_intro`,`tbl_class_member`.`class_member_last_access` AS `class_member_last_access`,`tbl_class_member`.`class_member_charge` AS `class_member_charge`,`tbl_class`.`class_name` AS `class_name`,`tbl_class`.`class_college` AS `class_college`,`tbl_class`.`class_year` AS `class_year`,`tbl_class`.`class_create_time` AS `class_create_time`,`tbl_class`.`class_charge` AS `class_charge`,`tbl_class`.`class_notice` AS `class_notice`,`tbl_class`.`class_member_num` AS `class_member_num`,`tbl_class_user`.`realName` AS `realName` from ((`tbl_class_member` join `tbl_class` on((`tbl_class`.`class_id` = `tbl_class_member`.`class_id`))) join `tbl_class_user` on((`tbl_class_user`.`uid` = `tbl_class_member`.`class_member_id`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_class` VALUES ('1', '06工商管理二班', '理学院', '2006', '1209086301', '1', '', '1');
INSERT INTO `tbl_class_member` VALUES ('1', '1', '1209086301', '0', '', null, '1209114875', '0');
INSERT INTO `tbl_class_privacy` VALUES ('1', '0', '0', '0', '0');
INSERT INTO `tbl_class_topic` VALUES ('1', '1', '1', '今年51不购物 购物就去家乐福', '<p><strong><font size=\\\"4\\\">本来我也是不怎么去家乐福</font></strong></p>\n<p><strong><font size=\\\"4\\\">因为经常不路过</font></strong></p>\n<p><strong><font size=\\\"4\\\">所以也就没感觉</font></strong></p>\n<p><strong><font size=\\\"4\\\">倒是家里人总和邻居大妈去家乐福买米买油</font></strong></p>\n<p><strong><font size=\\\"4\\\">这年头儿谁都不容易</font></strong></p>\n<p><strong><font size=\\\"4\\\">能省就省呗</font></strong></p>\n<p><strong><font size=\\\"4\\\">法国人在他们的祖国演出一场闹剧</font></strong></p>\n<p><strong><font size=\\\"4\\\">我们完全可以把他们当做一群跳梁小丑</font></strong></p>\n<p><strong><font size=\\\"4\\\">也不知道内位大人先是空穴来风家乐福的股东资金支持藏独</font></strong></p>\n<p><strong><font size=\\\"4\\\">然后就有风就起浪&nbsp;</font></strong></p>\n<p><strong><font size=\\\"4\\\">先是北京开始说51抵制家乐福</font></strong></p>\n<p><strong><font size=\\\"4\\\">这到好等不及51就开始大闹了</font></strong></p>\n<p><strong><font size=\\\"4\\\">在学校里找不到对象天天游戏实在无聊</font></strong></p>\n<p><strong><font size=\\\"4\\\">去起起哄也是好的</font></strong></p>\n<p><strong><font size=\\\"4\\\">还能被当做爱国斗士何乐而不为呢</font></strong></p>\n<p><strong><font size=\\\"4\\\">爱法国不顺眼没钱去法国游行</font></strong></p>\n<p><strong><font size=\\\"4\\\">大使馆那边管的还严被抓起来扣上破坏安定团结也不好</font></strong></p>\n<p><strong><font size=\\\"4\\\">lv的东西 gucci的东西听听可以抵制嘛买不起何谈抵制</font></strong></p>\n<p><strong><font size=\\\"4\\\">想找个法国人说理&nbsp; 偏偏周围还没有</font></strong></p>\n<p><strong><font size=\\\"4\\\">抬头一看&nbsp; 家乐福好吧 就你了</font></strong></p>\n<p><strong><font size=\\\"4\\\">找不到管事儿的教育找个“看大门的”总可以吧</font></strong></p>\n<p><strong><font size=\\\"4\\\">于是乎&nbsp; 各位同仁奔走相告 呼朋唤友 扯上旗帜举着标语前往家乐福</font></strong></p>\n<p><strong><font size=\\\"4\\\">对着镜头好似多光荣呢</font></strong></p>\n<p><strong><font size=\\\"4\\\">其实呢&nbsp; 早被外国人当笑柄了</font></strong></p>\n<p><strong><font size=\\\"4\\\">有时间各位可以去看看youtube就知道了</font></strong></p>\n<p><strong><font size=\\\"4\\\">也不知道这集会有没有去公安局备案</font></strong></p>\n<p><strong><font size=\\\"4\\\">如果没有&nbsp;&nbsp; 人家现在是不管你</font></strong></p>\n<p><strong><font size=\\\"4\\\">等到管的时候&nbsp; 你们可全傻眼吧</font></strong></p>\n<p><strong><font size=\\\"4\\\">当年抵制日货的先辈还在上海的大狱里蹲着</font></strong></p>\n<p><strong><font size=\\\"4\\\">有些事情做了就完了</font></strong></p>\n<p><strong><font size=\\\"4\\\">例如你爱国&nbsp; 还要什么结果&nbsp; 那算哪门子爱国</font></strong></p>\n<p><strong><font size=\\\"4\\\">某君</font></strong></p>\n<p><strong><font size=\\\"4\\\">听说大家要去抵制家乐福</font></strong></p>\n<p><strong><font size=\\\"4\\\">马上用碧欧泉套装对自己的皮肤进行呵护要上镜头不光鲜不行</font></strong></p>\n<p><strong><font size=\\\"4\\\">嘴里嚼着安利的维生素拿起自己的sony相机穿上耐克的鞋子套上杰克琼斯的外套</font></strong></p>\n<p><strong><font size=\\\"4\\\">出门前不忘记戴上施华洛世奇的水晶耳钉</font></strong></p>\n<p><strong><font size=\\\"4\\\">抵达现场&nbsp; 人山人海</font></strong></p>\n<p><strong><font size=\\\"4\\\">只见一瓶娃哈哈的纯净水对着从家乐福购物出来的无辜百姓空袭而去看你购物太热降降温</font></strong></p>\n<p><strong><font size=\\\"4\\\">在然后 现场&nbsp; canon&nbsp; 奥林巴斯 sony&nbsp; 松下的相机快门照个不停声场要曝光汉奸</font></strong></p>\n<p><strong><font size=\\\"4\\\">。。。。。。。。。。。。。。分割</font></strong></p>\n<p><strong><font size=\\\"4\\\">终于散场&nbsp; 只见标语贴的到处都是各式各样的草书让人叹为观止地上一边狼藉纯净水瓶遍地都是乐坏了收破烂儿的大娘</font></strong></p>\n<p><strong><font size=\\\"4\\\">今天活动到此结束&nbsp; 晚上还要去法国公司的wow里去下副本没有来得及买饭就去麦当劳里大快朵颐&nbsp; 忙坏了里面的服</font></strong><strong><font size=\\\"4\\\">务生</font></strong></p>\n<p><strong><font size=\\\"4\\\">睡觉前不忘发条短信抵制家乐福&nbsp; 短信通过法国阿尔卡特的设备转到无数人的手机上</font></strong></p>\n<p><strong><font size=\\\"4\\\">于是无数人纷纷乘着雪铁龙的出租车&nbsp;&nbsp; 坐着法国TGV动车组赶来响应</font></strong></p>\n<p><strong><font size=\\\"4\\\">一女生表示家里还有很多法国的cd香水&nbsp; 用完了就改资生堂</font></strong></p>\n<p><strong><font size=\\\"4\\\">男生们呢&nbsp; 使用intel&nbsp; or&nbsp; amd 处理器的电脑进入自己的微软windows操作系统&nbsp; 要让全中国的网民一起抵制家乐福</font></strong></p>\n<p><strong><font size=\\\"4\\\">号召家乐福的员工罢工&nbsp; 不过没有补贴啊&nbsp; 你们损失点儿那叫爱国</font></strong></p>\n<p><strong><font size=\\\"4\\\">家乐福员工辞职的话你才是真爱国&nbsp; 工作嘛&nbsp; 偶们大学毕业还找不到呢&nbsp; 哪顾的上你啊</font></strong></p>\n<p><strong><font size=\\\"4\\\">家乐福倒闭了 35岁以上的叔叔阿姨对着其他国有超市35以下的门槛儿叹气&nbsp; 孩子还要上学</font></strong></p>\n<p><strong><font size=\\\"4\\\">于是他们找到当时声称家乐福倒了他们不会失业的爱国青年们</font></strong></p>\n<p><strong><font size=\\\"4\\\">爱国青年教育他们道：你们舍小家的爱国精神我们很欣赏&nbsp; 可是你们的工作 我们也无能为力啊</font></strong></p>\n<p><strong><font size=\\\"4\\\">可是国家什么时候支持过抵制这儿抵制那儿呢</font></strong></p>\n<p><strong><font size=\\\"4\\\">如果你真正强大还用去抵制</font></strong></p>\n<p><strong><font size=\\\"4\\\">好好的爱国活动最后闹剧收场</font></strong></p>\n<p><strong><font size=\\\"4\\\">没出事还好&nbsp; 出了事情&nbsp; 你们就傻眼了吧冲动的感情可不是你拦的住的</font></strong></p>\n<p><strong><font size=\\\"4\\\">到时候秋后算账&nbsp; 一个都跑不了</font></strong></p>\n<p><strong><font size=\\\"4\\\">还给</font></strong><strong><font size=\\\"4\\\">把爱国的光环换成破坏社会治安</font></strong></p>\n<p><strong><font size=\\\"4\\\">也不知道闹什么闹好好工作不比什么都强</font></strong></p>\n<p><strong><font size=\\\"4\\\">改变能改变的&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;不能改变的你添什么乱</font></strong></p>\n<p><strong><font size=\\\"4\\\">所谓枪打出头鸟&nbsp;&nbsp;&nbsp;&nbsp;低调点没人说你不爱国</font></strong></p>\n<p><strong><font size=\\\"4\\\">今年流行语&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 今天你抵制了没</font></strong></p>\n<p><strong><font size=\\\"4\\\">4月21日的新闻联播里已经了播出了家乐福从未支持过藏独的新闻</font></strong></p>\n<p><strong><font size=\\\"4\\\">网民们子虚乌有的理由也该转移下方向了吧别老和自己过不去</font></strong></p>\n<p><strong><font size=\\\"4\\\">我呢</font></strong></p>\n<p><strong><font size=\\\"4\\\">看腻味了一些人强奸别人意志诋毁别人的癔症行为</font></strong></p>\n<p><strong><font size=\\\"4\\\">所以</font></strong></p>\n<p><strong><font size=\\\"4\\\">逆反一把&nbsp;&nbsp; 今年51不购物 购物就去家乐福</font></strong></p>', '1209114551', null, '0', '0', '0', null);
INSERT INTO `tbl_class_topic` VALUES ('1', '2', '1', '韩寒“对不起”', '发现自己错了<br />韩寒，对不起。<br />那天在新闻网页上看到他的文字，觉得挺喜欢。<br />就到论坛上贴了一个。<br />可没想到反响这么大，这真的让我很崩溃。<br /><br />我现在想说韩寒怎么了。<br />我发的他的贴，是应为觉得他的观点挺理性的，个人很喜欢，就贴了下。<br />韩寒和我们一样是个青少年，同样有权利发表自己的心声吧。<br /><br />今天早论坛上看到一个贴“韩寒你算个什么东西”。<br />真的让我很崩溃，没想到会让这么多人反感韩寒了。<br />这完全不是我的本意。<br />我今天真的很难过，看到太多有关韩寒的帖子了突然。<br /><br />这让我怀疑是不是我那贴的原因。<br />也许是有些小自恋吧。<br />说的都是什么话，什么叫韩寒不是真的爱国。<br />我想说你就真的爱过么，韩寒他怎么就被你们说的不爱国了呢，我就不明白了。<br /><br />本小姐这会真的是被气晕了。<br />你可以不喜欢韩寒，但是不可以攻击人家。<br />听见没坏蛋们。<br />讨厌的东东。', '1209114666', null, '0', '0', '0', null);
INSERT INTO `tbl_class_topic` VALUES ('1', '3', '1', '韩寒“对不起”家乐福', '发现自己错了<br />韩寒，对不起。<br />那天在新闻网页上看到他的文字，觉得挺喜欢。<br />就到论坛上贴了一个。<br />可没想到反响这么大，这真的让我很崩溃。<br /><br />我现在想说韩寒怎么了。<br />我发的他的贴，是应为觉得他的观点挺理性的，个人很喜欢，就贴了下。<br />韩寒和我们一样是个青少年，同样有权利发表自己的心声吧。<br /><br />今天早论坛上看到一个贴“韩寒你算个什么东西”。<br />真的让我很崩溃，没想到会让这么多人反感韩寒了。<br />这完全不是我的本意。<br />我今天真的很难过，看到太多有关韩寒的帖子了突然。<br /><br />这让我怀疑是不是我那贴的原因。<br />也许是有些小自恋吧。<br />说的都是什么话，什么叫韩寒不是真的爱国。<br />我想说你就真的爱过么，韩寒他怎么就被你们说的不爱国了呢，我就不明白了。<br /><br />本小姐这会真的是被气晕了。<br />你可以不喜欢韩寒，但是不可以攻击人家。<br />听见没坏蛋们。<br />讨厌的东东。', '1209114741', null, '0', '0', '0', '韩寒,家乐福');
INSERT INTO `tbl_class_user` VALUES ('1', '林啊');
