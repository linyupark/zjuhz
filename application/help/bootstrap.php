<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:bootstrap.php
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/help/interlayers/'.PATH_SEPARATOR.
                 '../../application/help/models/');
//echo get_include_path();exit;

/** Zend_Loader */
require_once('Zend/Loader.php');
/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 项目配置文档对象 */
Zend_Registry::set('iniHelp', new Zend_Config_Ini('../../common/Ini/Help.ini'));

//Zend_Session::rememberMe(3600);
/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon', new Zend_Session_Namespace('common'));
/** 项目SESSION */
Zend_Registry::set('sessHelp', new Zend_Session_Namespace('help'));
/** 项目ACL */
Zend_Registry::set('aclHelp', new Zend_Acl());

/** Zend_Layout */
Zend_Layout::startMvc(array(
    'layoutPath' => '../../application/layouts/',
    'layout' => 'main'));

/** run */
Zend_Controller_Front::getInstance()
    ->registerPlugin(new AclModel(Zend_Registry::get('sessCommon')->role))
	->setDefaultModule('help')
    ->setControllerDirectory('../../application/help/controllers/')
    ->throwExceptions(true)
    ->dispatch();
