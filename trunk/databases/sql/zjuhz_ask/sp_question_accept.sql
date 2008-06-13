DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_ask`.`sp_question_accept` $$
CREATE PROCEDURE `zjuhz_ask`.`sp_question_accept` (IN param_rid INT(10),IN param_qid INT(10),IN param_quid INT(10),IN param_ruid INT(10),OUT out_offer SMALLINT(5))
BEGIN


    DECLARE myruid INT(10);
    DECLARE myoffer SMALLINT(5) DEFAULT -1;

    IF param_rid > 0 AND param_qid > 0 AND param_quid > 0 AND param_ruid > 0 THEN

        SELECT uid INTO myruid FROM tbl_ask_reply WHERE rid = param_rid AND qid = param_qid AND uid = param_ruid AND status = 0;
        IF myruid = param_ruid THEN

            SELECT offer INTO myoffer FROM tbl_ask_question WHERE qid = param_qid AND status = 0;
            IF myoffer >= 0 THEN

                UPDATE tbl_ask_question SET status = 1 WHERE qid = param_qid LIMIT 1;
                UPDATE tbl_ask_reply SET status = 1 WHERE rid = param_rid LIMIT 1;
                UPDATE tbl_ask SET solved = solved + 1, unsolved = unsolved - 1 WHERE uid = param_quid LIMIT 1;
                UPDATE tbl_ask SET answer = answer + 1, point = point + myoffer, expertPoint = expertPoint + myoffer WHERE uid = param_ruid LIMIT 1;

            END IF;

        END IF;

    END IF;

    SET out_offer = myoffer;


END $$

DELIMITER ;