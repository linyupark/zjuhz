<?php
	
	/**
	 * 描述: zjuhz 信息发布系统
	 */
	
	/** set_include_path */
	set_include_path(get_include_path().PATH_SEPARATOR.'../../common/Custom/'.
										PATH_SEPARATOR.'../../application/info/models/');

	/** Zend_Controller_Front */
	require_once 'Zend/Controller/Front.php';

	/** Zend_Loader autoloader callback */
	Zend_Loader::registerAutoload();
	
	/** Registry database connection */
	$db_info = new Zend_Config_Ini('../../common/Ini/Info.ini','dbconn');
	Zend_Registry::set('db_info',Zend_Db::factory($db_info->adapter,$db_info->params->toArray()));
	
	/** Registry session */
	Zend_Registry::set('sess_info',new Zend_Session_Namespace('info'));
	Zend_Registry::set('sess',new Zend_Session_Namespace('common'));
	
	/* Registry Acl set **/
	$info_acl = new Zend_Acl();
	$info_acl->addRole(new Zend_Acl_Role('editor'));
	$info_acl->addRole(new Zend_Acl_Role('admin'));
	$info_acl->allow('editor', null, array('post','login'));
	$info_acl->allow('admin');
	Zend_Registry::set('acl_info',$info_acl);
	
	/** run */
	$info_front = Zend_Controller_Front::getInstance();
	$info_front->throwExceptions(true);
	$info_front->setControllerDirectory('../../application/info/controllers/');
	$info_front->dispatch();