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
    Zend_Registry::set('iniInfo', new Zend_Config_Ini('Ini/Info.ini'));
	Zend_Registry::set('sessInfo',new Zend_Session_Namespace('info'));
	Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
	
	/* Layout */
	Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/'));
	
	/** run */
	$front = Zend_Controller_Front::getInstance();
	$front->setDefaultModule('info');
	$front->throwExceptions(true);
	$front->registerPlugin(new InfoAcl(Zend_Registry::get('iniInfo')));
	$front->setControllerDirectory('../../application/info/controller');
	$front->dispatch();
        