DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`sp_register` $$
CREATE PROCEDURE `zjuhz_user`.`sp_register` (IN param_username CHAR(16),IN param_password CHAR(16),IN param_realName CHAR(6),IN param_sex CHAR(1),IN param_regIp CHAR(15),IN param_ikey CHAR(10),OUT out_uid INT(10))
BEGIN


    DECLARE uidCnt TINYINT(1) DEFAULT 0;
    DECLARE uidParent INT(10) DEFAULT 0;
    DECLARE nameMy CHAR(6);
    DECLARE uidMy INT(10) DEFAULT 0;
    DECLARE statusMy TINYINT(1) DEFAULT 0;
    DECLARE statusCard TINYINT(1) DEFAULT 0;

    SELECT COUNT(uid) INTO uidCnt FROM tbl_user WHERE username = param_username LIMIT 1;
    IF uidCnt = 0 THEN

        SELECT uid,cname,status INTO uidParent,nameMy,statusCard FROM tbl_user_address_card WHERE cid = param_ikey AND status IN (0,1,3);
        IF uidParent IS NULL OR uidParent = 0 THEN
            SET uidParent = 0;
            SET statusMy = 0;
        ELSEIF uidParent > 0 AND nameMy = param_realName THEN
            SET statusMy = 2;
        ELSE
            SET statusMy = 1;
        END IF;

        IF statusMy = 2 THEN

            INSERT INTO tbl_user (username,password,realName,nickname,sex,regIp,ikey,iuid) VALUES (param_username,md5(param_password),param_realName,param_realName,param_sex,param_regIp,param_ikey,uidParent);
            SET uidMy = LAST_INSERT_ID();

            IF uidMy > uidParent THEN

                INSERT INTO tbl_user_ext (uid,status,lastIp,lastLogin) VALUES (uidMy,statusMy,param_regIp,UNIX_TIMESTAMP());
                INSERT INTO tbl_user_contact (uid) VALUES (uidMy);

                IF uidMy > 0 THEN
                    UPDATE tbl_user_address_card SET iuid = uidMy,status = statusMy WHERE cid = param_ikey AND status IN (0,1,3) LIMIT 1;

                    IF statusCard = 0 OR statusCard = 1 THEN
                        UPDATE tbl_user_invite_log SET isReg = 'Y' WHERE cid = param_ikey;
                    END IF;
                END IF;

            END IF;

        END IF;

    END IF;

    SET out_uid = uidMy;


END $$

DELIMITER ;