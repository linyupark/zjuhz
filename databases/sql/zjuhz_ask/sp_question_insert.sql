DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_ask`.`sp_question_insert` $$
CREATE PROCEDURE `zjuhz_ask`.`sp_question_insert` (IN param_uid INT(10),IN param_title CHAR(30),IN param_content TEXT,IN param_tags CHAR(30),IN param_sid SMALLINT(5),IN param_offer SMALLINT(5),IN param_anonym ENUM('Y','N'),OUT out_qid INT(10))
BEGIN


    INSERT INTO tbl_ask_question (uid, title, content, tags, sid, offer, anonym) VALUES (param_uid, param_title, param_content, param_tags, param_sid, param_offer, param_anonym);
    IF (ROW_COUNT()) THEN

        SET out_qid = LAST_INSERT_ID();
        UPDATE tbl_ask SET point = point - param_offer, question = question + 1, unsolved = unsolved + 1 WHERE uid = param_uid;

    ELSE

        SET out_qid = 0;

    END IF;


END $$

DELIMITER ;