--
-- 数据库: 'zjuhz_user'
--

--
-- 导出表中的数据 'tbl_user'
--

INSERT INTO tbl_user (uid, username, password, realName, nickname, everName, sex, birthday, hometown_p, hometown_c, hometown_a, location_p, location_c, location_a, lastModi, regIp, regTime, ikey, iuid) VALUES
(1, 'zjuhz', 'e10adc3949ba59abbe56e057f20f883e', '校友会', '校友会', NULL, '男', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2008-04-29 09:16:55', NULL, 0);

--
-- 导出表中的数据 'tbl_user_contact'
--

INSERT INTO tbl_user_contact (uid, mobile, eMail, qq, msn, address, postcode, other, lastModi) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

--
-- 导出表中的数据 'tbl_user_ext'
--

INSERT INTO tbl_user_ext (uid, status, lastIp, lastLogin, editNick, initAsk, initClass) VALUES
(1, 2, NULL, 0, 'N', 'N', 'N');
