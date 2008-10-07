<?php

/**
 * 操作zjuhz_class数据(临时)
 * */

class Zclass
{
    public $_db;
    
    function __construct()
    {
        $iniDb = new Zend_Config_Ini('Ini/Db.ini');
        $params = $iniDb->default->params->toArray();
        $params['dbname'] = 'zjuhz_class';
        
        $this->_db = Zend_Db::factory($iniDb->default->adapter, $params);
    }
}

?>