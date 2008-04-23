DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_ask`.`sp_sort_counter` $$
CREATE PROCEDURE `zjuhz_ask`.`sp_sort_counter` (IN param_sid1 SMALLINT(5),IN param_sid2 SMALLINT(5),IN param_sid3 SMALLINT(5),IN param_filed CHAR(8))
BEGIN


    IF param_filed = 'question' OR param_filed = 'solved' OR param_filed = 'closed' OR param_filed = 'overtime' THEN

        SET @sql = NULL;

        IF param_sid1 > 0 THEN

            SET @sql = CONCAT('UPDATE tbl_ask_sort SET ', param_filed, ' = ', param_filed, ' + 1 WHERE sid = ', param_sid1, ' LIMIT 1');
            PREPARE sqlstmt FROM @sql;
            EXECUTE sqlstmt;
            DEALLOCATE PREPARE sqlstmt;

        END IF;

        IF param_sid2 > 0 THEN

            SET @sql = CONCAT('UPDATE tbl_ask_sort SET ', param_filed, ' = ', param_filed, ' + 1 WHERE sid = ', param_sid2, ' LIMIT 1');
            PREPARE sqlstmt FROM @sql;
            EXECUTE sqlstmt;
            DEALLOCATE PREPARE sqlstmt;

        END IF;

        IF param_sid3 > 0 THEN

            SET @sql = CONCAT('UPDATE tbl_ask_sort SET ', param_filed, ' = ', param_filed, ' + 1 WHERE sid = ', param_sid3, ' LIMIT 1');
            PREPARE sqlstmt FROM @sql;
            EXECUTE sqlstmt;
            DEALLOCATE PREPARE sqlstmt;

        END IF;

    END IF;


END $$

DELIMITER ;