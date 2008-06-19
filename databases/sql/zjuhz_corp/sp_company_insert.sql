DELIMITER $$

DROP PROCEDURE IF EXISTS `zjuhz_corp`.`sp_company_insert` $$
CREATE PROCEDURE `zjuhz_corp`.`sp_company_insert` (IN param_cid CHAR(10), IN param_uid INT(10),
    IN param_name VARCHAR(50), IN param_industry TINYINT(3),
    IN param_property TINYINT(3), IN param_province CHAR(8),
    IN param_city CHAR(11), IN param_phone VARCHAR(13)
)
BEGIN


    INSERT INTO tbl_corp_company (cid, uid, name, industry, property, province, city) VALUES (param_cid, param_uid, param_name, param_industry, param_property, param_province, param_city);
    IF (ROW_COUNT()) THEN

        INSERT INTO tbl_corp_company_contact (cid, uid, phone) VALUES (param_cid, param_uid, param_phone);
        IF (ROW_COUNT()) THEN

            INSERT INTO tbl_corp_company_biz (cid, uid) VALUES (param_cid, param_uid);
            UPDATE tbl_corp SET auditing = auditing + 1 WHERE uid = param_uid;

        END IF;

    END IF;


END $$

DELIMITER ;