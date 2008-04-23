DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_ask`.`sp_reply_insert` $$
CREATE PROCEDURE `zjuhz_ask`.`sp_reply_insert` (IN param_qid INT(10), IN param_uid INT(10), IN param_content TEXT, IN param_anonym ENUM("Y","N"), IN param_offer SMALLINT(5), OUT out_rid INT(10))
BEGIN


    INSERT INTO tbl_ask_reply (qid, uid, content, anonym) VALUES (param_qid, param_uid, param_content, param_anonym);
    IF (ROW_COUNT()) THEN

        SET out_rid = LAST_INSERT_ID();

        UPDATE tbl_ask SET point = point + param_offer, reply = reply + 1 WHERE uid = param_uid LIMIT 1;
        UPDATE tbl_ask_question SET reply = reply + 1, replyTime = UNIX_TIMESTAMP() WHERE qid = param_qid LIMIT 1;

    ELSE

        SET out_rid = 0;

    END IF;


END $$

DELIMITER ;