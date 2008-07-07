<?php

class Biz
{
    public $_db;
    
    function __construct()
    {
        $iniDb = new Zend_Config_Ini('Ini/Db.ini');
        $params = $iniDb->default->params->toArray();
        $params['dbname'] = 'zjuhz_corp';
        $this->_db = Zend_Db::factory($iniDb->default->adapter, $params);
    }
    
    function get($limit)
    {
        $this->_db->query('SET NAMES "utf8"');
        return $this->_db->fetchAll('SELECT `cid`,`name` FROM `tbl_corp_company` 
                                    ORDER BY RAND() LIMIT '.$limit);
    }
}

?>