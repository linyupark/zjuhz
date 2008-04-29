DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_user`.`sp_login` $$
CREATE PROCEDURE `zjuhz_user`.`sp_login` (IN param_username CHAR(16),IN param_password CHAR(32),IN param_lastIp CHAR(15))
BEGIN

    DECLARE myuid INT(10) DEFAULT 0; /* for login */

    SELECT uid INTO myuid FROM tbl_user WHERE username = param_username AND password = param_password;
    IF myuid > 0 THEN

        SELECT user.*,ext.*,contact.* FROM tbl_user AS user,tbl_user_ext AS ext,tbl_user_contact AS contact WHERE user.uid = myuid AND user.uid = ext.uid AND user.uid = contact.uid;

        IF param_lastIp IS NOT NULL OR param_lastIp != '' THEN

            UPDATE tbl_user_ext SET lastIp = param_lastIp,lastLogin = UNIX_TIMESTAMP() WHERE uid = myuid LIMIT 1;

        END IF;

    ELSE

        SELECT myuid AS uid;

    END IF;

END $$

DELIMITER ;