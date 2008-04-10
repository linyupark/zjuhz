<?php

	// 设置加载路径
	set_include_path(get_include_path().PATH_SEPARATOR.'../../application/info/models/');
	
	// 启动ZF
	require_once 'Zend/Controller/Front.php';

	// 自动加载必要模块
	Zend_Loader::registerAutoload();
	
	// 全局注册
	$iniInfo = new Zend_Config_Ini('Ini/Info.ini');
	$dbInfo = Zend_Db::factory($iniInfo->db->adapter, $iniInfo->db->params->toArray());
	Zend_Registry::set('dbInfo', $dbInfo);

	$server = new Zend_XmlRpc_Server();
	$server->setClass('InfoService','rpcInfo');
	echo $server->handle();
