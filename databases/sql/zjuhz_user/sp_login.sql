DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`sp_login` $$
CREATE PROCEDURE `zjuhz_user`.`sp_login` (IN param_userName CHAR(16),IN param_passWord CHAR(16),IN param_lastIp CHAR(15))
BEGIN

    DECLARE mylUid INT(10) DEFAULT 0; /* for login */

    SELECT uid INTO mylUid FROM tbl_user WHERE userName = param_userName AND passWord = md5(param_passWord);
    IF mylUid > 0 THEN

        SELECT user.uid,user.userName,user.passWord,user.realName,user.nickName,user.sex,ext.status,ext.editNick,ext.initAsk FROM tbl_user AS user,tbl_user_ext AS ext WHERE user.uid = mylUid AND user.uid = ext.uid;

        UPDATE tbl_user_ext SET lastIp = param_lastIp,lastLogin = UNIX_TIMESTAMP() WHERE uid = mylUid LIMIT 1;

    ELSE

        SELECT mylUid AS uid;

    END IF;

END $$

DELIMITER ;