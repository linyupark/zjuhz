<?php

/**
 * @category   zjuhz.com
 * @package    ask
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:index.php
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/ask/strap/'.PATH_SEPARATOR.
                 '../../application/ask/models/');
//echo get_include_path();exit;

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** Registry 配置文档对象 */
Zend_Registry::set('iniAsk',new Zend_Config_Ini('../../common/Ini/Ask.ini'));

Zend_Session::rememberMe(3600);
/** 公用SESSION,包含如验证码,用户基本资料等 */
/*$sessCommon = new Zend_Session_Namespace('common');
if (!isset($sessCommon->initialized))
{
    Zend_Session::regenerateId();
    $sessCommon->initialized = true;
}*/
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
Zend_Registry::set('sessAsk',new Zend_Session_Namespace('ask'));

/** run */
Zend_Controller_Front::run('../../application/ask/controllers/');
