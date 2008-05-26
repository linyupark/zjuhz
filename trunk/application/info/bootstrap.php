<?php
	
	// 设置加载路径
	set_include_path(get_include_path().
				PATH_SEPARATOR.'../../common/Custom/'.
				PATH_SEPARATOR.'../../application/info/models/');

	// 启动ZF
	require_once 'Zend/Controller/Front.php';

	// 自动加载必要模块
	Zend_Loader::registerAutoload();
	// 全局注册
	$iniInfo = new Zend_Config_Ini('Ini/Info.ini');
	$iniDb = new Zend_Config_Ini('Ini/Db.ini');
	$params = $iniDb->default->params->toArray();
	$params['dbname'] = $iniInfo->db->params->dbname;
	$dbInfo = Zend_Db::factory($iniDb->default->adapter, $params);
	Zend_Registry::set('dbInfo', $dbInfo);
    Zend_Registry::set('iniInfo', $iniInfo);
	Zend_Registry::set('sessInfo', new Zend_Session_Namespace('info'));
	Zend_Registry::set('sessCommon', new Zend_Session_Namespace('common'));
	
	Zend_Db_Table::setDefaultAdapter($dbInfo);
	
	/* Layout */
	Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/', 'layout' => 'main'));
	
	/** run */
	$front = Zend_Controller_Front::getInstance();
	$front->throwExceptions(true)
	      ->registerPlugin(new InfoAcl(Zend_Registry::get('sessCommon')))
	      ->setControllerDirectory('../../application/info/controllers/')
	      ->dispatch();
        