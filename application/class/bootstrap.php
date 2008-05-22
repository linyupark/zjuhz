<?php

date_default_timezone_set('Asia/Shanghai');

/** set_include_path */
set_include_path(get_include_path().
					 PATH_SEPARATOR.'../../common/Custom/'.
					 PATH_SEPARATOR.'../../application/class/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

$iniClass = new Zend_Config_Ini('Ini/Class.ini');
$iniDb = new Zend_Config_Ini('Ini/Db.ini');
$params = $iniDb->default->params->toArray();
$params['dbname'] = $iniClass->db->params->dbname;
$dbClass = Zend_Db::factory($iniDb->default->adapter, $params);
Zend_Registry::set('dbClass', $dbClass);
Zend_Registry::set('iniClass', $iniClass);
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
Zend_Registry::set('sessClass', new Zend_Session_Namespace('class'));

/* Layout */
Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/', 'layout' => 'main'));

define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);


/** run */
$info_front = Zend_Controller_Front::getInstance();
$info_front->setDefaultModule('class')
           ->throwExceptions(true)
           ->registerPlugin(new ClassAcl(Zend_Registry::get('sessCommon')))
           ->setControllerDirectory('../../application/class/controllers/')
           ->dispatch();
