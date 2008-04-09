<?php

// 设置加载路径
	set_include_path(get_include_path().PATH_SEPARATOR.'../../application/info/models/');

	require_once 'Zend/Loader.php';
	require_once 'Zend/XmlRpc/Server.php';

	$server = new Zend_XmlRpc_Server();
	$server->setClass('InfoService','rpcInfo');
	echo $server->handle();
