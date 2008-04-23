/*
MySQL Data Transfer
Source Host: localhost
Source Database: zjuhz_info
Target Host: localhost
Target Database: zjuhz_info
Date: 2008-4-23 16:59:02
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
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

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
  `entity_top` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`entity_id`),
  UNIQUE KEY `entity_title` (`entity_title`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_role
-- ----------------------------
CREATE TABLE `tbl_role` (
  `role_id` int(10) unsigned NOT NULL auto_increment,
  `role_name` char(25) NOT NULL,
  `role_nickname` char(25) NOT NULL,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
CREATE TABLE `tbl_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` char(50) NOT NULL,
  `user_password` char(32) NOT NULL,
  `user_role` char(20) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for vi_entity
-- ----------------------------
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vi_entity` AS select `tbl_category`.`category_name` AS `category_name`,`tbl_user`.`user_name` AS `user_name`,`tbl_role`.`role_nickname` AS `role_nickname`,`tbl_category`.`category_icon` AS `category_icon`,`tbl_category`.`category_pub` AS `category_pub`,`tbl_entity`.`entity_id` AS `entity_id`,`tbl_entity`.`entity_title` AS `entity_title`,`tbl_entity`.`entity_view_num` AS `entity_view_num`,`tbl_entity`.`entity_pub_time` AS `entity_pub_time`,`tbl_entity`.`entity_mod_time` AS `entity_mod_time`,`tbl_entity`.`entity_tag` AS `entity_tag`,`tbl_entity`.`entity_pub` AS `entity_pub`,`tbl_entity`.`entity_content` AS `entity_content`,`tbl_entity`.`entity_top` AS `entity_top`,`tbl_entity`.`category_id` AS `category_id`,`tbl_entity`.`user_id` AS `user_id` from (((`tbl_category` join `tbl_entity` on((`tbl_entity`.`category_id` = `tbl_category`.`category_id`))) join `tbl_user` on((`tbl_user`.`user_id` = `tbl_entity`.`user_id`))) join `tbl_role` on((`tbl_role`.`role_name` = `tbl_user`.`user_role`)));

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_category` VALUES ('28', 'sound_none', '活动预告', '0');
INSERT INTO `tbl_category` VALUES ('27', 'radio', '浙大动态', '1');
INSERT INTO `tbl_entity` VALUES ('39', '27', '关于Prof.Eric Siu-Wai Kong学术报告“Polymers,nanomaterials and devices”的通知', '1', '43', '1207723980', '1207794300', '<div>应浙江大学浙江加州国际纳米技术研究院陈帆青教授邀请，Eric Siu-Wai Kong教授拟来我校做专题学术报告。欢迎全校师生和校外科研人员参加。</div>\r\n<div><br><strong>报告主题：</strong>Polymers,nanomaterials and devices<br><strong>报 告 人：</strong>Prof.Eric Siu-Wai Kong<br><strong>报告时间：</strong>2008年4月3日 上午9点30分开始<br><strong>报告地点：</strong>玉泉校区曹光彪大楼326会议室<br><strong>主 持 人：</strong>陈帆青教授</div>\r\n<div>&nbsp;</div>\r\n<div><strong>陈帆青教授简介:</strong><br>浙江加州国际纳米技术研究院国际生物技术中心首席科学家,生物医学博士，2000\r\n年至今为美国加州大学劳伦斯伯克利国家实验室生命科学部高通量与纳米生物实验室负责人，国家级纳米分子铸造中心纳米毒理、纳米生物传感器项目PI、博士后\r\n导师。目前为浙江大学特聘兼职教授、博士导师、并为复旦大学生命科学院兼职教授。</div>\r\n<div>&nbsp;</div>\r\n<div><strong>报告人简介：</strong></div>\r\n<div>Prof. Kong was born and educated in Hong Kong and the United\r\nStates. He attended Diocesan Boys’ School in Hong Kong. After graduate&nbsp;\r\nfrom Diocesan Boys’ School, Professor Kong proceeded to study chemistry\r\nin the United States: attending University of California at Berkeley\r\nfor undergraduate study while studying polymer chemistry at Rensselaer\r\nPolytechnic Institute under the guidance of Professor Bernhard\r\nWunderlich. After the doctoral study, Professor Kong Pursued his\r\npostdoctoral research activities under the tutelage of Professor Garth\r\nWilkes and Professor James McGrath. He then joined and made\r\ncontributions at organizations including NASA Ames Research Center,\r\nSandia National Laboratories, Stanford University, and Hewlett Packard\r\nLaboratories. The highest rank that Professor Kong has reached is\r\nDirector of Research &amp; Development as well as Chief Technology\r\nOfficer. Professor Kong enjoys classical and jazz music and plays the\r\nflute. He is happily married to Clara Zhang. Dr. Kong has two sons.\r\nThroughout His career, Prof. Kong has received grants over\r\nU.S.$1,800,000 for his research endeavor. His current research interest\r\nis in nanomaterials, polymers, and devices. Dr. Kong is the founder of\r\na nanotechnology company by the name of Nanophotonic Semiconductors,\r\nInc., California, USA.&nbsp; Dr. Kong has been with Shanghai Jiao Tong\r\nUniversity since 2005 as a research professor at the Research Institute\r\nof Micro/Nanometer Science and Technology.</div>\r\n<div><br><strong>报告内容简介：</strong></div>\r\n<div>The importance and performance of nanoelectronic devices have both\r\nincreased significantly over the last twenty years, evolving from a\r\nfield with great promise for new materials and applications to a real\r\nindustry with a few commercial products on the market.&nbsp; Broader\r\nacceptance of nanostructured semiconductors will hinge on the\r\ndevelopment of materials with competitive properties and superior\r\nprocessability, yielding high-performance devices from a significantly\r\nless expensive fabrication process.&nbsp; The incorporation of\r\nfirst-generation materials into commercial applications has required\r\nimpressive efforts by scientists and device engineers to yield stable\r\nworking devices.&nbsp; <br>&nbsp;<br>Nanoelectronics based on semiconducting\r\ncarbon nanotubes have attracted much interest due to their superior\r\nproperties such as high electrical conductance, high mobility, and\r\nchemical inertness, compared to ones based on conventional\r\nsemiconducting materials.&nbsp; In this presentation, we will show the\r\nconstruction of carbon nanotube field effect transistors based on a\r\nnanowelding technique. Devices such as photovoltaic cells will be\r\ndiscussed based on polymers and nanomaterials.&nbsp; In addition, devices\r\nbased on silicon nanowires and silicon carbide nanowires will be\r\ncovered.&nbsp; The use of carbon nanotubes to build sensors and detectors\r\nwill also be discussed.<br>&nbsp;<br>Functionalization of nanosurfaces has\r\nunlocked a new era in the development and applications of hybrid\r\nnanomaterials.&nbsp; Binding polymers to carbon nanotubes promises to be one\r\nof the most intriguing prospects in this technology since the\r\nindividual properties of the two materials can be combined to give a\r\nnovel hybrid nanomaterial with good mechanical strength, high thermal\r\nconductivity, and excellent processing ability.&nbsp; The synthesis and\r\ncharacterization of functionalized carbon nanotubes will be discussed\r\nduring this seminar. </div>', '通知,学术报告', '1', '1');
INSERT INTO `tbl_entity` VALUES ('38', '27', '“浙大东方论坛”学术讲座之十八——科技考古与文物的科学研究', '1', '8', '1207723860', '0', '　　主　　讲：中国科学院 干福熹 院士 <br>\r\n　　主　　持：浙江大学 罗卫东  教授<br>\r\n　　时　　间：4月7日（周一）下午3:00-5:00<br>\r\n　　地　　点：紫金港校区国际会议中心136室<br>\r\n　　主　　办：浙江大学<br>\r\n　　承　　办：浙江大学社会科学研究院<br>\r\n 　　　　　　　浙江大学材料与化学工程学院<br>\r\n　　　　　　　浙江大学文物保护和鉴定研究中心<br>\r\n<br>\r\n附：干福熹&nbsp;院士简介 <br>\r\n<br>\r\n　　浙江杭州人，1952年毕业于浙江大学化工系，1960年2月获苏联科学院硅酸盐化学研究所副博士学位，1957年建立了我国第一个光学玻璃试制基\r\n地，1980年当选为中国科学院院士，1993年当选为第三世界科学院院士。现为中国科学院学部委员、国务院学位委员会委员、中国科技协会常务委员、浙江\r\n大学教授（博士生导师）。 <br>\r\n<br>\r\n　　干院士主要从事光学、激光和光电信息技术方面的研究，曾任中国科学院上海光学精密机械研究所所长，历任中国科学院上海光学精密机械研究所研究员、中科\r\n院技术科学部委员、上海市科协副主席、中国硅酸盐学会副理事长，现任中国科学院上海光学精密机械研究所研究员。近年来，着重从事硅酸盐质文物（古玻璃、古\r\n陶瓷、古玉器）的科学研究，是我国这个领域的开拓者。他主持组织召开第一届《中国古玻璃国际学术研讨会》，出版专著《中国古代玻璃技术研究》，并与浙江省\r\n考古所合作亲自主持《良渚文化物质遗产》的科学研究。他是我国提出采用现代高科技手段对中国文物进行科学研究的倡导者之一，现被国家文物局聘为科技顾问。\r\n干院士还计划在浙江大学、复旦大学招收关于文物（古玻璃、古陶瓷、古玉）研究方向的博士生。', '讲座,科学,考古,文物', '1', '0');
INSERT INTO `tbl_entity` VALUES ('40', '27', '甘肃省代表团访问浙江大学中国西部发展研究院', '1', '17', '1207797360', '1207798320', '<font size=\\\"3\\\">&nbsp;&nbsp;&nbsp; 4月2日，甘肃省西部开发办副主任曹力耕、天水市市长助理吉建安带领甘肃省天水市代表团访问浙江大学中国西部发展研究院。常务副院长金祥荣等有关领导和教\r\n授热情接待了代表团。双方围绕《关中——天水经济区发展规划》课题进行了深入的交流和研讨，并就其它相关事项进行了洽谈。 <br><br>&nbsp;&nbsp;&nbsp; 《关中——天水经济区发展规划》课题是日前国家发改委、国务院西部办委托西部院对以西安为中心的关中经济带进行发展规划的研究项目。该规划是我国近年来继\r\n成渝、环渤海和北部湾之后的又一个重大区域性战略规划。甘肃省和天水市政府对此课题的开展非常重视，特组成代表团专程赴西部院就课题前期调研等工作进行了\r\n沟通和交流。 <br><br>&nbsp;&nbsp;&nbsp; 出席座谈会的还有西部院院长助理董雪兵、经济学院汪炜教授、管理学院魏江教授等课题组成员。</font>', '西部,甘肃,研究院', '1', '0');
INSERT INTO `tbl_entity` VALUES ('41', '27', '哈佛大学脂类研究中心主任康景轩博士受聘浙大客座教授', '14', '17', '1207799940', '0', '<font size=\\\"3\\\">&nbsp;&nbsp;&nbsp; 2008年4月8日晚，美国哈佛大学脂类研究中心主任、留美华人企业家联合会副会长康景轩博士（Dr. Jingxuan Kang）受聘浙江大学客座教授仪式暨学术报告会在紫金港国际会议中心举行。 <br><br>&nbsp;&nbsp;&nbsp; 康景轩博士1984年毕业于广东医学院，1993年获加拿大阿尔伯特大学博士学位，1994年起在哈佛大学医学院工作。现任美国哈佛大学副教授，哈佛大学\r\n脂类研究中心主任，在ω-3脂肪酸生物医学研究领域有极高建树，近5年在国际著名学术期刊上发表论文38篇，在近三年间《自然》科学杂志四次介绍他的研究\r\n成果。2004年、2006年康景轩博士分别成功地培育出世界上第一头能够自身合成ω-3脂肪酸的哺乳动物(老鼠)和家禽(猪)。该技术将使人们很容易获\r\n得ω-3脂肪酸, 从而有效预防心脑血管疾病，大大降低心脑血管发病率。2006年康景轩博士因此而获得诺贝尔医学奖提名。 <br><br>&nbsp;&nbsp;&nbsp; 受聘仪式之后，康景轩博士为师生们作了一场题为“Omega-3多不饱和脂肪酸和健康”的学术报告，全面介绍了Omega-3多不饱和脂肪酸作用机理及其\r\n在脂肪酸领域的研究情况，并简要向同学们介绍了他的学习、研究与奋斗历程。生工食品学院、动科院、医学院、药学院等有关学院的教师、研究生、本科生参加了\r\n受聘仪式并聆听了学术报告。 </font>', '康景轩,哈佛', '1', '0');
INSERT INTO `tbl_role` VALUES ('1', 'admin', '管理员');
INSERT INTO `tbl_role` VALUES ('2', 'staff', '投稿员');
INSERT INTO `tbl_user` VALUES ('1', 'administrator', '4297f44b13955235245b2497399d7a93', 'admin');
INSERT INTO `tbl_user` VALUES ('2', 'staff', '4297f44b13955235245b2497399d7a93', 'staff');
INSERT INTO `tbl_user` VALUES ('14', '小林', '4297f44b13955235245b2497399d7a93', 'staff');
INSERT INTO `tbl_user` VALUES ('15', 'jojo', '7510d498f23f5815d3376ea7bad64e29', 'staff');
INSERT INTO `tbl_user` VALUES ('16', 'fangzx', '034ebd0b55e851ea364a6c255c592881', 'staff');
