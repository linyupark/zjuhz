DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`invite_creating` $$
CREATE PROCEDURE `zjuhz_user`.`invite_creating` (IN param_inviteuid INT(10),IN param_realname CHAR(20))
BEGIN



    DECLARE invitekey CHAR(10) DEFAULT NULL;
    DECLARE invitetime INT(10) DEFAULT UNIX_TIMESTAMP();
    DECLARE result CHAR(10) DEFAULT NULL;

    IF param_inviteuid > 0 AND LENGTH(param_realname) >= 3 THEN

        SET invitekey = LEFT(MD5(param_inviteuid + invitetime + param_realname),10);

        IF LENGTH(invitekey) = 10 THEN

            INSERT INTO tbl_user_invite_detail (invitekey,inviteuid,realname,invitetime) VALUES (invitekey,param_inviteuid,param_realname,invitetime);

            UPDATE tbl_user_invite SET sum = sum + 1 WHERE inviteuid = param_inviteuid LIMIT 1;

            IF @@error_count = 0 THEN

                SET result = invitekey;

            END IF;

        END IF;

    END IF;

    SELECT result;



END $$

DELIMITER ;