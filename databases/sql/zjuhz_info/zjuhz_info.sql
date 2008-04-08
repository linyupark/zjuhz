/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_info
Target Host: localhost
Target Database: zjuhz_info
Date: 2008-4-8 17:10:00
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

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
  `user_id` int(11) NOT NULL,
  `entity_view_num` int(11) NOT NULL default '0',
  `entity_pub_time` int(11) default '0',
  `entity_mod_time` int(11) default '0',
  `entity_content` text NOT NULL,
  `entity_tag` tinytext,
  `entity_pub` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`entity_id`),
  UNIQUE KEY `entity_title` (`entity_title`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_category` VALUES ('1', 'radio', '浙大动态', '1');
INSERT INTO `tbl_category` VALUES ('2', 'page_white_world', '各地校友动态', '1');
INSERT INTO `tbl_category` VALUES ('9', 'anchor', '杭州校友会动态', '1');
INSERT INTO `tbl_category` VALUES ('10', 'group', '校友互助信息', '0');
INSERT INTO `tbl_category` VALUES ('11', 'rainbow', '校友企业展示', '0');
INSERT INTO `tbl_category` VALUES ('12', 'talk', '校友访谈', '0');
INSERT INTO `tbl_entity` VALUES ('4', '1', '浙大助小山村引来国家级科技项目', '1', '35', '1205305320', '1207643520', '<div style=\\\"text-align: justify; text-indent: 2em;\\\">今年初，地处浙西南山区的龙泉市安仁镇项边村，一个只有1400余总人口的小山村，成了国家科技部启动的首批120个新农村建设科技示范村（试点）之一。\r\n作为浙江大学龙泉市现代农业技术合作推广中心的村级服务点，依靠浙江大学的技术支撑，项边村引来了首个国家级科技项目。日前,该项目在当地正式启动. <font size=\\\"2\\\"><br></font><font size=\\\"2\\\"><br>&nbsp;&nbsp;&nbsp; 项边村是被称为“中国黑木耳第一乡”的安仁镇的中心村，该村70%的农户从事食用菌生产，60%的村民总收入也来自食用菌生产，异地种植黑木耳已达全国\r\n16个省（市），具有典型的“一村一品”的产业特色。2007年6月，浙江大学龙泉市现代农业技术合作推广中心成立，项边村被列为中心的村级服务点，由浙\r\n大教授担任首席专家引导该村食用菌产业的发展。目前，在浙大专家教授的指导下，当地农户成立了省内首个村级菌种研究所、省内首个黑木耳合作社，打下了良好\r\n的科技示范基础。 </font><font size=\\\"2\\\"><br></font><font size=\\\"2\\\"><br>&nbsp;&nbsp;&nbsp; 据介绍，“项边村新农村建设科技示范（试点）”项目将以浙江大学为主要技术依托单位，探索远郊山区食用菌产业循环发展的模式，为浙江西南山区乃至我国东南\r\n沿海欠发达山区的新农村建设提供示范。项目计划通过采用当地工业主导产业——木制玩具业的加工废弃物（木屑）、速生菇木林及经济林的修剪枝条等作为黑木耳\r\n生产原料，达到原料供给与生态保护的动态平衡。同时，开展废菌棒资源化多层次利用，生产生态有机肥、园艺基质及生物有机饲料，减少农业面源污染，实现生态\r\n食用菌（黑木耳）循环产业链。 <br></font><font size=\\\"2\\\"><br></font>&nbsp;&nbsp;&nbsp; 新农村建设科技示范（试点）工作由国家科技部启动实施，旨在充分发挥科技在社会主义新农村建设中的示范导向作用，推动一批依靠科技创新建设社会主义新农村\r\n的示范，实现“富民、惠民”。示范（试点）以五年计划为周期，项目实施期限原则为2-3年。今年是第一批，共启动了73个新农村建设科技示范乡镇（试\r\n点）、120个新农村建设科技示范村（试点）。浙江省共有6个乡镇、村列入试点，以浙江大学为主要技术依托单位的有3个。另外两个是：绍兴市诸暨市山下湖\r\n镇和衢州市龙游县模环乡钱家村。</div>', '科技项目,浙大', '0');
INSERT INTO `tbl_entity` VALUES ('6', '1', '浙大大学英语教学中心被评为“全国三八红旗集体”', '1', '7', '1205305500', '1205377380', '<font style=\\\"text-indent: 2em;\\\" size=\\\"2\\\">&nbsp;&nbsp;&nbsp; 日前，从有关部门获悉，浙江大学外国语言文化与国际交流学院大学英语教学中心被评为“全国三八红旗集体”，浙大材化学院高分子复合材料研究所副所长陈红征教授被评为浙江省“三八红旗手”。 <br><br>&nbsp;&nbsp;&nbsp; 大学英语教学中心是一个以女性占绝大多数的团队，承担着浙大全校23,000余名本科生的大学英语教学任务。她们长期坚持教学改革，在全国率先提出了“以\r\n学生为中心”的主题教学模式，编写出版了后被评为国家级精品教材的配套教材——《新编大学英语》系列教材，全国有600多所院校、近200万在校生使用该\r\n教材；她们主动关注学生生理、心理健康成长，组织学生开展第二课堂活动，成为学生的良师益友；她们积极投身社会主义新农村建设，先后对长兴县、安吉县\r\n1000余名中小学英语教师进行了培训；她们还承担了“浙江省政府门户网站英文版内容建设及维护服务”项目，每天完成千余字的动态新闻翻译工作。近年来，\r\n该团队中有三分之一以上的教师曾获得各类优秀教师奖，多人次被评为“教书育人”先进个人。 <br><br>&nbsp;&nbsp;&nbsp; 陈红征教授长期坚持工作在教学、科研第一线。她不断钻研教学业务，为本科生和研究生传授知识，得到学生的一致好评。先后获得浙江大学优秀班主任、浙江大学\r\n先进工作者，浙江省工会“事业家庭兼顾型”先进个人称号；她先后参与和负责20多项国内外重大科研基金项目，在著名学术刊物发表SCI收录论文120多\r\n篇，获国家授权发明专利4项,\r\n获国家教委科技进步三等奖1项，在有机光电功能材料、有机复合半导体材料与器件，以及新型结构纳米材料的制备与功能化方面取得突出成果。 <br><br>&nbsp;&nbsp;&nbsp; 三八红旗手（先进集体）的评选是全国妇联开展的一项历史悠久的活动，主要是表彰为社会主义建设及为所在行业和单位做出杰出贡献的妇女同志。此前，浙江大学\r\n教育学院妇女研究中心、浙一医院周建英教授分别获得2004年“全国三八红旗集体” 和2003年全国三八红旗手称号。</font>', '浙大', '0');
INSERT INTO `tbl_entity` VALUES ('7', '2', '锐军校友的小闺女在中国之声主持《小喇叭》节目了，真牛！', '2', '0', '1205305620', '0', '<div style=\\\"text-indent: 2em;\\\">以下是北京校友会副秘书长陈锐军学长的贴。能有这么可爱能干的小女儿，实在是有福之人啊！<br><br>==============================================<br>小女儿陈馨悦喜欢朗诵，去年开始在中央人民广播电台参加小主持人培训班学习，成绩突出，音色甜美，很受老师喜欢。最近老师安排她参加了《小喇叭》节目主持，参加音乐广播剧录制。<br><br>昨天晚上，中央人民广播电台《小喇叭》节目播放了她主持的一期节目，全家人围坐一起听着孩子的声音，通过无线电波传到天涯海角……<br><br>看到孩子健康成长，做父亲的我，忍不住落泪了……<br><br>有兴趣的校友，可以在线听听这期节目！<br>==============================================<br><br>大家可以通过访问下面帖子来在线收听节目<br><a href=\\\"http://www.zuaa.cn/bbs/ShowPost.asp?ThreadID=8843\\\" target=\\\"_blank\\\">http://www.zuaa.cn/bbs/ShowPost.asp?ThreadID=8843</a></div>', null, '0');
INSERT INTO `tbl_entity` VALUES ('8', '1', '浙江动态测试1', '1', '1', '120530522', '0', '今年初，地处浙西南山区的龙泉市安仁镇项边村，一个只有1400余总人口的小山村，成了国家科技部启动的首批120个新农村建设科技示范村（试点）之一。作为浙江大学龙泉市现代农业技术合作推广中心的村级服务点，依靠浙江大学的技术支撑，项边村引来了首个国家级科技项目。日前,该项目在当地正式启动.\r\n\r\n    项边村是被称为“中国黑木耳第一乡”的安仁镇的中心村，该村70%的农户从事食用菌生产，60%的村民总收入也来自食用菌生产，异地种植黑木耳已达全国 16个省（市），具有典型的“一村一品”的产业特色。2007年6月，浙江大学龙泉市现代农业技术合作推广中心成立，项边村被列为中心的村级服务点，由浙大教授担任首席专家引导该村食用菌产业的发展。目前，在浙大专家教授的指导下，当地农户成立了省内首个村级菌种研究所、省内首个黑木耳合作社，打下了良好的科技示范基础。\r\n\r\n    据介绍，“项边村新农村建设科技示范（试点）”项目将以浙江大学为主要技术依托单位，探索远郊山区食用菌产业循环发展的模式，为浙江西南山区乃至我国东南沿海欠发达山区的新农村建设提供示范。项目计划通过采用当地工业主导产业——木制玩具业的加工废弃物（木屑）、速生菇木林及经济林的修剪枝条等作为黑木耳生产原料，达到原料供给与生态保护的动态平衡。同时，开展废菌棒资源化多层次利用，生产生态有机肥、园艺基质及生物有机饲料，减少农业面源污染，实现生态食用菌（黑木耳）循环产业链。\r\n\r\n    新农村建设科技示范（试点）工作由国家科技部启动实施，旨在充分发挥科技在社会主义新农村建设中的示范导向作用，推动一批依靠科技创新建设社会主义新农村的示范，实现“富民、惠民”。示范（试点）以五年计划为周期，项目实施期限原则为2-3年。今年是第一批，共启动了73个新农村建设科技示范乡镇（试点）、120个新农村建设科技示范村（试点）。浙江省共有6个乡镇、村列入试点，以浙江大学为主要技术依托单位的有3个。另外两个是：绍兴市诸暨市山下湖镇和衢州市龙游县模环乡钱家村。', '测试,浙大', '0');
INSERT INTO `tbl_entity` VALUES ('9', '1', '西部最佳：科比-布莱恩特(湖人)', '2', '4', '1207626060', '0', '<p>　　科比-布莱恩特上周帮助湖人队取得3连胜，而且三个对手均是来自西部的球队(开拓者、<a href=\\\"http://nba.sports.sina.com.cn/team/Mavericks.shtml\\\" target=\\\"_blank\\\" class=\\\"akey\\\">小牛</a>和国王)。科比上周场均得到30分，9.3个篮板球和6次助攻。作为一名后卫，场均近10个篮板球已是不可多得，然而科比在外线同样是难以阻挡，他上周的三分命中率居然高达68.8%(17投11中)。目前湖人队战绩为53胜24负，已经追平了马刺队位居西部第二。</p>\r\n<p>　　科比上周表现回顾：</p>\r\n<p>　　4月2日胜开拓者：36分，13篮板，7助攻，3抢断；</p>\r\n<p>　　4月4日胜小牛：25分，10篮板，6助攻；</p>\r\n<p>　　4月6日胜国王：29分，5篮板，5助攻，2抢断。</p>\r\n<p>　　<strong>其他获得提名的球员有：</strong>活塞队的罗德尼-斯塔基，黄蜂队的克里斯-保罗，76人队的安德烈-伊戈达拉，国王队的凯文-马丁，猛龙队的拉索-内斯特洛维奇，爵士队的德隆-威廉姆斯和小牛队的德克-诺维茨基。其中，诺维茨基是在腿伤未痊愈的情况下被迫复出。他回来之后，小牛连克勇士与<a href=\\\"http://nba.sports.sina.com.cn/team/Suns.shtml\\\" target=\\\"_blank\\\" class=\\\"akey\\\">太阳</a>，保住了当时摇摇欲坠的西部季后赛名额。</p>', null, '0');
INSERT INTO `tbl_entity` VALUES ('10', '1', 'MySQL事务处理和锁定语句', '2', '1', '1207637880', '0', '<pre><span>START TRANSACTION | BEGIN [WORK]</span></pre>\r\n			<pre><span>COMMIT [WORK] [AND [NO] CHAIN] [[NO] RELEASE]</span></pre>\r\n			<pre><span>ROLLBACK [WORK] [AND [NO] CHAIN] [[NO] RELEASE]</span></pre>\r\n			<pre><span>SET AUTOCOMMIT = {0 | 1}</span></pre>\r\n			<p><span>START TRANSACTION</span>或<span>BEGIN</span>语句可以开始一项新的事务。<span>COMMIT</span>可以提交当前事务，是变更成为永久变更。<span>ROLLBACK</span>可以\r\n			回滚当前事务，取消其变更。<span>SET \r\n			AUTOCOMMIT</span>语句可以禁用或启用默认的<span>autocommit</span>模式，用于当前连接。</p>\r\n			<p>自选的<span>WORK</span>关键词被支持，用于<span>COMMIT</span>和<span>RELEASE</span>，与<span>CHAIN</span>和<span>RELEASE</span>子句。<span>CHAIN</span>和<span>RELEASE</span>可以被用于对事务完成进行附加控制。<span>Completion_type</span>系统变量的值决定了默认完成的性质。请参见<a href=\\\"http://dev.mysql.com/doc/refman/5.1/zh/database-administration.html#server-system-variables\\\" title=\\\"5.3.3.&nbsp;Server System Variables\\\">5.3.3节，“服务器系统变量”</a>。</p>\r\n			<p><span>AND CHAIN</span>子句会在当前事务结束时，立刻启动一个新事务，并且新事务与刚结束的事务有相同的隔离等级。<span>RELEASE</span>子句在终止了当前事务后，会让服务器断开与当前客户端的连接。包含<span>NO</span>关键词可以抑制<span>CHAIN</span>或<span>RELEASE</span>完成。如果<span>completion_type</span>系统变量被设置为一定的值，使连锁或释放完成可以默认进行，此时<span>NO</span>关键词有用。</p>\r\n			<p>默认情况下，<span>MySQL</span>采用<span>autocommit</span>模式运行。这意味着，当您执行一个用于更新（修改）表的语句之后，<span>MySQL</span>立刻把更新存储到磁盘中。</p>\r\n			<p>如果您正在使用一个事务安全型的存储引擎（如<span>InnoDB, BDB</span>或<span>NDB</span>簇），则您可以使用以下语句禁用<span>autocommit</span>模式：</p>\r\n			<pre><span>SET AUTOCOMMIT=0;</span></pre>\r\n			<p>通过把<span>AUTOCOMMIT</span>变量设置为零，禁用<span>autocommit</span>模式之后，您必须使用<span>COMMIT</span>把变更存储到磁盘中，或着如果您想要忽略从事务开始进行以来做出的变更，使用<span>ROLLBACK</span>。</p>\r\n			<p>如果您想要对于一个单一系列的语句禁用<span>autocommit</span>模式，则您可以使用<span>START \r\n			TRANSACTION</span>语句：</p>\r\n			<pre><span>START TRANSACTION;</span></pre>\r\n			<pre><span>SELECT @A:=SUM(salary) FROM table1 WHERE type=1;</span></pre>\r\n			<pre><span>UPDATE table2 SET summary=@A WHERE type=1;</span></pre>\r\n			<pre><span>COMMIT;</span></pre>\r\n			<p>使用<span>START TRANSACTION</span>，<span>autocommit</span>仍然被禁用，直到您使用<span>COMMIT</span>或<span>ROLLBACK</span>结束事务为止。然后<span>autocommit</span>模式恢复到原来的状态。</p>\r\n			<p><span>BEGIN</span>和<span>BEGIN WORK</span>被作为<span>START \r\n			TRANSACTION</span>的别名受到支持，用于对事务进行初始化。<span>START \r\n			TRANSACTION</span>是标准的<span>SQL</span>语法，并且是启动一个<span>ad-hoc</span>事务的推荐方法。<span>BEGIN</span>语句与<span>BEGIN</span>关键词的使用不同。<span>BEGIN</span>关键词可以启动一个<span>BEGIN...END</span>复合语句。后者不会开始一项事务。请参见<a href=\\\"http://dev.mysql.com/doc/refman/5.1/zh/stored-procedures.html#begin-end\\\" title=\\\"20.2.7.&nbsp;BEGIN ... END Compound Statement\\\">20.2.7节，“BEGIN \r\n		... END复合语句”</a>。</p>\r\n			<p>您也可以按照如下方法开始一项事务：</p>\r\n			<pre><span>START TRANSACTION WITH CONSISTENT SNAPSHOT;</span></pre>\r\n			<p><span>WITH CONSISTENT SNAPSHOT</span>子句用于启动一个一致的读取，用于具有此类功能的存储引擎。目前，该子句只适用于<span>InnoDB</span>。该子句的效果与发布一个<span>START \r\n			TRANSACTION</span>，后面跟一个来自任何<span>InnoDB</span>表的<span>SELECT</span>的效果一样。请参见<a href=\\\"http://dev.mysql.com/doc/refman/5.1/zh/storage-engines.html#innodb-consistent-read\\\" title=\\\"15.2.10.4.&nbsp;Consistent Non-Locking Read\\\">15.2.10.4节，“一致的非锁定读”</a>。</p>', '语句,mysql,事务处理', '0');
INSERT INTO `tbl_entity` VALUES ('16', '1', '会造成隐式提交的语句', '2', '0', '1207637880', '0', '<p>以下语句（以及同义词）均隐含地结束一个事务，似乎是在执行本语句前，您已经进行了一个<span>COMMIT</span>。</p>\r\n			<p>\r\n			<span>·<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n			</span></span><span>ALTER \r\n			FUNCTION</span><span>,\r\n			<span>ALTER PROCEDURE</span>,\r\n			<span>ALTER TABLE</span>,\r\n			<span>BEGIN</span>,\r\n			<span>CREATE DATABASE</span>,\r\n			<span>CREATE FUNCTION</span>,\r\n			<span>CREATE INDEX</span>,\r\n			<span>CREATE PROCEDURE</span>,\r\n			<span>CREATE TABLE</span>,\r\n			<span>DROP DATABASE</span>,\r\n			<span>DROP FUNCTION</span>,\r\n			<span>DROP INDEX</span>,\r\n			<span>DROP PROCEDURE</span>,\r\n			<span>DROP TABLE</span>,\r\n			<span>LOAD MASTER DATA</span>,\r\n			<span>LOCK TABLES</span>,\r\n			<span>RENAME TABLE</span>,\r\n			<span>SET AUTOCOMMIT=1</span>,\r\n			<span>START TRANSACTION</span>,\r\n			<span>TRUNCATE TABLE</span>,\r\n			<span>UNLOCK TABLES</span>. </span></p>\r\n			<p>\r\n			<span>·<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n			</span></span>当当前所有的表均被锁定时，<span>UNLOCK TABLES</span>可以提交事务。</p>\r\n			<p>\r\n			<span>·<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n			</span></span><span>CREATE \r\n			TABLE</span><span>, <span>\r\n			CREATE DATABASE</span> <span>DROP DATABASE</span>,\r\n			<span>TRUNCATE TABLE</span>,\r\n			<span>ALTER FUNCTION</span>,\r\n			<span>ALTER PROCEDURE</span>,\r\n			<span>CREATE FUNCTION</span>,\r\n			<span>CREATE PROCEDURE</span>,\r\n			<span>DROP FUNCTION</span></span>和<span><span>DROP \r\n			PROCEDURE</span>等语句会导致一个隐含提交。</span></p>\r\n			<p>\r\n			<span>·<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n			</span></span><span>InnoDB</span>中的<span>CREATE \r\n			TABLE</span>语句被作为一个单一事务进行处理。这意味着，来自用户的<span>ROLLBACK</span>不会撤销用户在事务处理过程中创建的<span>CREATE \r\n			TABLE</span>语句。</p>\r\n			<p>事务不能被嵌套。这是隐含<span>COMMIT</span>的结果。当您发布一个<span>START \r\n			TRANSACTION</span>语句或其同义词时，该<span>COMMIT</span>被执行，用于任何当前事务。</p>', '语句,mysql,事务处理', '0');
INSERT INTO `tbl_user` VALUES ('1', 'admin', '4297f44b13955235245b2497399d7a93', 'admin', null);
INSERT INTO `tbl_user` VALUES ('2', 'public2', '4297f44b13955235245b2497399d7a93', 'staff', '1|2');
