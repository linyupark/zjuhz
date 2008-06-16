<?php

date_default_timezone_set('Asia/Shanghai');

/** set_include_path */
set_include_path(get_include_path().
					 PATH_SEPARATOR.'../../common/Custom/'.
					 PATH_SEPARATOR.'../../application/company/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

$iniCompany = new Zend_Config_Ini('Ini/Company.ini');
$iniDb = new Zend_Config_Ini('Ini/Db.ini');
$params = $iniDb->default->params->toArray();
$params['dbname'] = 'zjuhz_company';
$dbCompany = Zend_Db::factory($iniDb->default->adapter, $params);
Zend_Registry::set('dbCompany', $dbCompany);
Zend_Registry::set('iniCompany', $iniCompany);
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
Zend_Registry::set('sessCompany', new Zend_Session_Namespace('company'));

/* Layout */
Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/company', 'layout' => 'default'));

define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);


/** run */
$info_front = Zend_Controller_Front::getInstance();
$info_front->setDefaultModule('company')
           ->throwExceptions(true)
           ->registerPlugin(new companyAcl())
           ->setControllerDirectory('../../application/company/controllers/')
           ->dispatch();
