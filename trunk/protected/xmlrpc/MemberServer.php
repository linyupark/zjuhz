<?php

/**
 * @category   zjuhz.com
 * @package    xmlrpc
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberServer.php
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/member/interlayers/'.PATH_SEPARATOR.
                 '../../application/member/models/');
//echo get_include_path();exit;

/** Zend_Controller_Front */
require_once('Zend/Loader.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 项目配置文档对象 */
Zend_Registry::set('iniDb', new Zend_Config_Ini('../../common/Ini/Db.ini'));
Zend_Registry::set('iniMember',new Zend_Config_Ini('../../common/Ini/Member.ini'));


/** Set cache file */
$file = dirname(__FILE__).'/cache/MemberServer.cache';
/** Zend_XmlRpc_Server */
$server = new Zend_XmlRpc_Server();
if (!Zend_XmlRpc_Server_Cache::get($file,$server))
{
	/** 开放可供调用的类或函数 */
	$server->setClass('MemberService','rpcMember');
	/** 生成缓存 */
    Zend_XmlRpc_Server_Cache::save($file,$server);
}

echo $server->handle();
