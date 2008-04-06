/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_info
Target Host: localhost
Target Database: zjuhz_info
Date: 2008-4-6 22:54:25
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
  PRIMARY KEY  (`entity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
INSERT INTO `tbl_entity` VALUES ('4', '1', '浙大助小山村引来国家级科技项目', '1', '0', '1205305320', '1206794220', '<DIV style=\\\"TEXT-INDENT: 2em; TEXT-ALIGN: justify\\\"><FONT size=2>今年初，地处浙西南山区的龙泉市安仁镇项边村，一个只有1400余总人口的小山村，成了国家科技部启动的首批120个新农村建设科技示范村（试点）之一。作为浙江大学龙泉市现代农业技术合作推广中心的村级服务点，依靠浙江大学的技术支撑，项边村引来了首个国家级科技项目。日前,该项目在当地正式启动. </FONT><FONT size=2><BR></FONT><FONT size=2><BR>&nbsp;&nbsp;&nbsp; 项边村是被称为“中国黑木耳第一乡”的安仁镇的中心村，该村70%的农户从事食用菌生产，60%的村民总收入也来自食用菌生产，异地种植黑木耳已达全国 16个省（市），具有典型的“一村一品”的产业特色。2007年6月，浙江大学龙泉市现代农业技术合作推广中心成立，项边村被列为中心的村级服务点，由浙大教授担任首席专家引导该村食用菌产业的发展。目前，在浙大专家教授的指导下，当地农户成立了省内首个村级菌种研究所、省内首个黑木耳合作社，打下了良好的科技示范基础。 </FONT><FONT size=2><BR></FONT><FONT size=2><BR>&nbsp;&nbsp;&nbsp; 据介绍，“项边村新农村建设科技示范（试点）”项目将以浙江大学为主要技术依托单位，探索远郊山区食用菌产业循环发展的模式，为浙江西南山区乃至我国东南沿海欠发达山区的新农村建设提供示范。项目计划通过采用当地工业主导产业——木制玩具业的加工废弃物（木屑）、速生菇木林及经济林的修剪枝条等作为黑木耳生产原料，达到原料供给与生态保护的动态平衡。同时，开展废菌棒资源化多层次利用，生产生态有机肥、园艺基质及生物有机饲料，减少农业面源污染，实现生态食用菌（黑木耳）循环产业链。 </FONT><FONT size=2><BR></FONT><FONT size=2><BR></FONT><FONT size=2>&nbsp;&nbsp;&nbsp; 新农村建设科技示范（试点）工作由国家科技部启动实施，旨在充分发挥科技在社会主义新农村建设中的示范导向作用，推动一批依靠科技创新建设社会主义新农村的示范，实现“富民、惠民”。示范（试点）以五年计划为周期，项目实施期限原则为2-3年。今年是第一批，共启动了73个新农村建设科技示范乡镇（试点）、120个新农村建设科技示范村（试点）。浙江省共有6个乡镇、村列入试点，以浙江大学为主要技术依托单位的有3个。另外两个是：绍兴市诸暨市山下湖镇和衢州市龙游县模环乡钱家村。</FONT></DIV>');
INSERT INTO `tbl_entity` VALUES ('6', '1', '浙大大学英语教学中心被评为“全国三八红旗集体”', '1', '0', '1205305500', '1205377380', '<font style=\\\"text-indent: 2em;\\\" size=\\\"2\\\">&nbsp;&nbsp;&nbsp; 日前，从有关部门获悉，浙江大学外国语言文化与国际交流学院大学英语教学中心被评为“全国三八红旗集体”，浙大材化学院高分子复合材料研究所副所长陈红征教授被评为浙江省“三八红旗手”。 <br><br>&nbsp;&nbsp;&nbsp; 大学英语教学中心是一个以女性占绝大多数的团队，承担着浙大全校23,000余名本科生的大学英语教学任务。她们长期坚持教学改革，在全国率先提出了“以\r\n学生为中心”的主题教学模式，编写出版了后被评为国家级精品教材的配套教材——《新编大学英语》系列教材，全国有600多所院校、近200万在校生使用该\r\n教材；她们主动关注学生生理、心理健康成长，组织学生开展第二课堂活动，成为学生的良师益友；她们积极投身社会主义新农村建设，先后对长兴县、安吉县\r\n1000余名中小学英语教师进行了培训；她们还承担了“浙江省政府门户网站英文版内容建设及维护服务”项目，每天完成千余字的动态新闻翻译工作。近年来，\r\n该团队中有三分之一以上的教师曾获得各类优秀教师奖，多人次被评为“教书育人”先进个人。 <br><br>&nbsp;&nbsp;&nbsp; 陈红征教授长期坚持工作在教学、科研第一线。她不断钻研教学业务，为本科生和研究生传授知识，得到学生的一致好评。先后获得浙江大学优秀班主任、浙江大学\r\n先进工作者，浙江省工会“事业家庭兼顾型”先进个人称号；她先后参与和负责20多项国内外重大科研基金项目，在著名学术刊物发表SCI收录论文120多\r\n篇，获国家授权发明专利4项,\r\n获国家教委科技进步三等奖1项，在有机光电功能材料、有机复合半导体材料与器件，以及新型结构纳米材料的制备与功能化方面取得突出成果。 <br><br>&nbsp;&nbsp;&nbsp; 三八红旗手（先进集体）的评选是全国妇联开展的一项历史悠久的活动，主要是表彰为社会主义建设及为所在行业和单位做出杰出贡献的妇女同志。此前，浙江大学\r\n教育学院妇女研究中心、浙一医院周建英教授分别获得2004年“全国三八红旗集体” 和2003年全国三八红旗手称号。</font>');
INSERT INTO `tbl_entity` VALUES ('7', '2', '锐军校友的小闺女在中国之声主持《小喇叭》节目了，真牛！', '2', '0', '1205305620', '0', '<div style=\\\"text-indent: 2em;\\\">以下是北京校友会副秘书长陈锐军学长的贴。能有这么可爱能干的小女儿，实在是有福之人啊！<br><br>==============================================<br>小女儿陈馨悦喜欢朗诵，去年开始在中央人民广播电台参加小主持人培训班学习，成绩突出，音色甜美，很受老师喜欢。最近老师安排她参加了《小喇叭》节目主持，参加音乐广播剧录制。<br><br>昨天晚上，中央人民广播电台《小喇叭》节目播放了她主持的一期节目，全家人围坐一起听着孩子的声音，通过无线电波传到天涯海角……<br><br>看到孩子健康成长，做父亲的我，忍不住落泪了……<br><br>有兴趣的校友，可以在线听听这期节目！<br>==============================================<br><br>大家可以通过访问下面帖子来在线收听节目<br><a href=\\\"http://www.zuaa.cn/bbs/ShowPost.asp?ThreadID=8843\\\" target=\\\"_blank\\\">http://www.zuaa.cn/bbs/ShowPost.asp?ThreadID=8843</a></div>');
INSERT INTO `tbl_user` VALUES ('1', 'admin', '4297f44b13955235245b2497399d7a93', 'admin', null);
INSERT INTO `tbl_user` VALUES ('2', 'public2', '4297f44b13955235245b2497399d7a93', 'editor', '1|2');
