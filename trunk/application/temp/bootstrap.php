<?php

/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/temp/models/');
//echo get_include_path();exit;

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 公用配置文档对象 */
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));

Zend_Session::rememberMe(3600);
/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));

/** Zend_Layout */
Zend_Layout::startMvc(array(
    'layoutPath' => '../../application/layouts/',
    'layout' => 'main'));

echo phpinfo();

/** run */
Zend_Controller_Front::getInstance()->setDefaultModule('temp')
                                    ->setControllerDirectory('../../application/temp/controllers/')
                                    ->throwExceptions(true)
                                    ->dispatch();
