DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`invite_register` $$
CREATE PROCEDURE `zjuhz_user`.`invite_register` (IN param_username CHAR(16),IN param_password CHAR(41),IN param_realname CHAR(20),IN param_regip CHAR(15),IN param_sex ENUM('M','F','S'),IN param_email VARCHAR(51),IN param_ikey CHAR(10),IN param_iuid INT(10))
BEGIN



    DECLARE get_realname CHAR(20);
    DECLARE set_status TINYINT(1) DEFAULT 0;
    DECLARE get_uid INT(10);
    DECLARE set_uid INT(10);
    DECLARE result TINYINT(3) DEFAULT 0;

    IF LENGTH(param_username) >= 3 AND LENGTH(param_realname) >= 3 AND LENGTH(param_ikey) = 10 AND param_iuid > 0 THEN

        /* ----- check invite start ----- */
        SELECT realname INTO get_realname FROM zjuhz_user.tbl_user_invite_detail WHERE status = 0 AND invitekey = param_ikey AND inviteuid = param_iuid;
        IF param_realname = get_realname THEN

            SET set_status = 1;


        ELSEIF get_realname IS NULL THEN

            SET result = -1;

        END IF;
        /* ----- check invite end ----- */


        IF result = 0 THEN

            SELECT uid INTO get_uid FROM zjuhz_user.tbl_user WHERE username = param_username;
            IF get_uid IS NULL THEN

                INSERT INTO zjuhz_user.tbl_user (username,password,realname,regip,invitekey,inviteuid) VALUES (param_username,password(md5(param_password)),param_realname,param_regip,param_ikey,param_iuid);

                SET set_uid = LAST_INSERT_ID();
                IF set_uid > 0 THEN

                    INSERT INTO zjuhz_user.tbl_user_extinfo (uid,status,lastip) VALUES (set_uid,set_status,param_regip);
                    INSERT INTO zjuhz_user.tbl_user_invite (inviteuid) VALUES (set_uid);
                    INSERT INTO zjuhz_user.tbl_user_moreinfo (uid,nickname,sex,email) VALUES (set_uid,set_uid,param_sex,param_email);

                    IF @@error_count = 0 THEN

                        IF set_status = 1 THEN

                            UPDATE zjuhz_user.tbl_user_invite SET success = success + 1 WHERE inviteuid = param_iuid LIMIT 1;
                            UPDATE zjuhz_user.tbl_user_invite_detail SET regtime = UNIX_TIMESTAMP(),reguid = set_uid,status = 1 WHERE invitekey = param_ikey AND inviteuid = param_iuid LIMIT 1;

                            SET result = 1;

                        ELSE

                            SET result = 2;

                        END IF;

                    END IF;

                END IF;

            ELSE

                SET result = -2;

            END IF;

        END IF;

    END IF;

    SELECT result;



END $$

DELIMITER ;