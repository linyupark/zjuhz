--
-- 导出表中的数据 `tbl_ask_sort`
--

INSERT INTO `tbl_ask_sort` (`sid`, `name`, `parent`, `pid`, `pName`, `child`, `question`, `solved`, `closed`, `overtime`) VALUES
(1, '请选择', 0, 0, '请选择', 7, 0, 0, 0, 0),
(2, '电脑/网络', 0, 2, '电脑/网络', 2, 0, 0, 0, 0),
(3, '生活/时尚', 0, 3, '生活/时尚', 2, 0, 0, 0, 0),
(4, '电子/数码', 0, 4, '电子/数码', 2, 0, 0, 0, 0),
(5, '购车/养车', 0, 5, '购车/养车', 12, 0, 0, 0, 0),
(6, '美容/减肥', 0, 6, '美容/减肥', 2, 0, 0, 0, 0),
(7, '美食/烹饪', 0, 7, '美食/烹饪', 2, 0, 0, 0, 0),
(8, '商业/理财', 0, 8, '商业/理财', 2, 0, 0, 0, 0),
(9, '硬件', 2, 2, '电脑/网络', 8, 0, 0, 0, 0),
(10, '反病毒', 2, 2, '电脑/网络', 0, 0, 0, 0, 0),
(11, '购物', 3, 3, '生活/时尚', 0, 0, 0, 0, 0),
(12, '生活百科', 3, 3, '生活/时尚', 0, 0, 0, 0, 0),
(13, '诺基亚', 4, 4, '电子/数码', 0, 0, 0, 0, 0),
(14, '三星', 4, 4, '电子/数码', 0, 0, 0, 0, 0),
(15, '奔驰', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(16, '宝马', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(17, '丰田', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(18, '本田', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(19, '通用', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(20, '福特', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(21, '桑塔纳', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(22, '金杯', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(23, '奇瑞', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(24, '现代', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(25, '雷诺', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(26, '悍马', 5, 5, '购车/养车', 0, 0, 0, 0, 0),
(27, '美白/防晒', 6, 6, '美容/减肥', 0, 0, 0, 0, 0),
(28, '减肥', 6, 6, '美容/减肥', 0, 0, 0, 0, 0),
(29, '烹饪常识', 7, 7, '美食/烹饪', 0, 0, 0, 0, 0),
(30, '海鲜', 7, 7, '美食/烹饪', 0, 0, 0, 0, 0),
(31, '主板', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(32, '内存', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(33, 'CPU', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(34, '显示器', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(35, '电源/机箱', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(36, '硬盘', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(37, '键盘/鼠标', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(38, '打印机/复印机', 9, 2, '电脑/网络', 0, 0, 0, 0, 0),
(39, '证券', 8, 8, '商业/理财', 0, 0, 0, 0, 0),
(40, '基金', 8, 8, '商业/理财', 0, 0, 0, 0, 0);
