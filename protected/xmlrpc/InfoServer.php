<?php

	// 设置加载路径
	set_include_path(get_include_path().PATH_SEPARATOR.'../../application/info/models/');
	
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

	$server = new Zend_XmlRpc_Server();
	$server->setClass('InfoService','rpcInfo');
	echo $server->handle();
