DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_ask`.`sp_sort_parent` $$
CREATE PROCEDURE `zjuhz_ask`.`sp_sort_parent` ()
BEGIN


    DECLARE done INT DEFAULT 0;
    DECLARE mysid SMALLINT(5);

    DECLARE c1 CURSOR FOR SELECT sid FROM tbl_ask_sort WHERE sid != 1 AND parent = 0;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

    OPEN c1;
    REPEAT
        FETCH c1 INTO mysid;
        IF NOT done THEN

            SELECT * FROM tbl_ask_sort WHERE sid = mysid OR parent = mysid;

        END IF;
    UNTIL done END REPEAT;
    CLOSE c1;



END $$

DELIMITER ;