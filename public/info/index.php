<?php
	
	/**
	 * [info] (c) zjuhz.com
	 * 描述: 信息发布系统
	 */
	
	/** set_include_path */
	set_include_path(get_include_path().PATH_SEPARATOR.'../../common/Custom/'.PATH_SEPARATOR.'../../application/member/models/');

	/** Zend_Controller_Front */
	require_once 'Zend/Controller/Front.php';

	/** Zend_Loader autoloader callback */
	Zend_Loader::registerAutoload();
	
	/** Registry database connection */
	$db_info = new Zend_Config_Ini('../../common/Ini/Info.ini','dbconn');
	Zend_Registry::set('db_info',Zend_Db::factory($db_info->adapter,$db_info->params->toArray()));
	
	/** run */
	Zend_Controller_Front::run('../../application/info/controllers/');