<?php

/**
 * 群组数据使用
 * */

class Events
{
    public $_db;
    
    function __construct()
    {
        $iniDb = new Zend_Config_Ini('Ini/Db.ini');
        $params = $iniDb->default->params->toArray();
        $params['dbname'] = 'zjuhz_events';
        $this->_db = Zend_Db::factory($iniDb->default->adapter, $params);
    }
}

?>