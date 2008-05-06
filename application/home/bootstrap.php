<?php

/** set_include_path */
set_include_path(get_include_path().
					 PATH_SEPARATOR.'../../common/Custom/'.
					 PATH_SEPARATOR.'../../application/info/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 公用配置文档对象 */
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));

/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));

Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/'));

/** run */
	$info_front = Zend_Controller_Front::getInstance();
	$info_front->setDefaultModule('home');
	$info_front->throwExceptions(false);
	$info_front->setControllerDirectory('../../application/home/controllers/');
	$info_front->dispatch();
