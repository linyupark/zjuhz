<?php

date_default_timezone_set('Asia/Shanghai');
    
/** set_include_path */
set_include_path(get_include_path().
				 PATH_SEPARATOR.'../../common/'.
				 PATH_SEPARATOR.'../../common/Custom/'.
				 PATH_SEPARATOR.'../../application/group/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

$iniGroup = new Zend_Config_Ini('Ini/Group.ini');
$iniDb = new Zend_Config_Ini('Ini/Db.ini');
$params = $iniDb->default->params->toArray();
$params['dbname'] = 'zjuhz_group';
$dbGroup = Zend_Db::factory($iniDb->default->adapter, $params);
Zend_Registry::set('dbGroup', $dbGroup);
Zend_Registry::set('iniGroup', $iniGroup);
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
Zend_Registry::set('sessGroup', new Zend_Session_Namespace('group'));

define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);

/** run */
$info_front = Zend_Controller_Front::getInstance();
$info_front->setDefaultModule('group')
           ->throwExceptions(true)
           ->registerPlugin(new GroupPreLoad())
           ->setControllerDirectory('../../application/group/controllers/')
           ->dispatch();

