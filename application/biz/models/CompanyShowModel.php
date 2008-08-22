<?php

    class CompanyShowModel
    {
        static function _dao()
        {
            $iniDb   = new Zend_Config_Ini('../../common/Ini/Db.ini');
    		$adapter = $iniDb->default->adapter;
    		$params  = $iniDb->default->params->toArray();
    		$params['dbname'] = 'zjuhz_corp';
 
			$dao = Zend_Db::factory($adapter, $params);

			$dao->query('set names utf8');
            return $dao;
        }
    }