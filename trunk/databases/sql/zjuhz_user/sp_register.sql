DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`sp_register` $$
CREATE PROCEDURE `zjuhz_user`.`sp_register` (IN param_userName CHAR(16),IN param_passWord CHAR(16),IN param_realName CHAR(16),IN param_sex ENUM('M','F','S'),IN param_regIp CHAR(15),IN param_ikey CHAR(10),OUT out_uid INT(10))
BEGIN


    DECLARE mycUid INT(10) DEFAULT 0; /* for check */
    DECLARE myiUid INT(10) DEFAULT 0; /* for invite */
    DECLARE myiRealName CHAR(16) DEFAULT NULL; /* for invite */
    DECLARE myrStatus TINYINT(1) DEFAULT 0; /* for register */
    DECLARE myrUid INT(10) DEFAULT 0; /* for register */
    DECLARE myTime INT(10) DEFAULT UNIX_TIMESTAMP();

    SELECT uid INTO mycUid FROM tbl_user WHERE userName = param_userName;
    IF mycUid IS NULL OR mycUid = 0 THEN

        SELECT iuid,realName INTO myiUid,myiRealName FROM tbl_user_invite_detail WHERE status = 0 AND ikey = param_ikey;
        IF myiUid IS NULL OR myiUid = 0 THEN
            SET myiUid = 0;
            SET myrStatus = 0;
        ELSEIF myiUid > 0 AND myiRealName = param_realName THEN
            SET myrStatus = 2;
        ELSE
            SET myrStatus = 1;
        END IF;

        INSERT INTO tbl_user (userName,passWord,realName,nickName,sex,regIp,ikey,iuid) VALUES (param_userName,md5(param_passWord),param_realName,param_realName,param_sex,param_regIp,param_ikey,myiUid);
        SET myrUid = LAST_INSERT_ID();

        IF myrUid > myiUid THEN

            INSERT INTO tbl_user_ext (uid,status,lastIp,lastLogin) VALUES (myrUid,myrStatus,param_regIp,myTime);

            IF myiUid > 0 THEN
              UPDATE tbl_user_invite_detail SET regTime = mytime,uid = myrUid,status = myrStatus WHERE status = 0 AND ikey = param_ikey LIMIT 1;
            END IF;
            IF myrStatus = 2 THEN
              UPDATE tbl_user_invite SET success = success + 1 WHERE iuid = myiUid LIMIT 1;
            END IF;

        END IF;

    END IF;

    SET out_uid = myrUid;


END $$

DELIMITER ;