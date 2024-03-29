<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:bootstrap.php
 */


/** set error_reporting */
error_reporting('ALL');

/** set gzip output */
//(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ? ob_start('ob_gzhandler') : ob_start());

/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
				 '../../common/'.PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/biz/interlayers/'.PATH_SEPARATOR.
                 '../../application/biz/models/');

/** Zend_Loader */
require_once('Zend/Loader.php');
/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** Config */
Zend_Registry::set('iniCommon', new Zend_Config_Ini('../../common/Ini/Config.ini'));
Zend_Registry::set('iniCompany', new Zend_Config_Ini('../../common/Ini/Biz.ini'));

/** Session */
Zend_Registry::set('sessCommon', new Zend_Session_Namespace('common'));
Zend_Registry::set('sessCompany', new Zend_Session_Namespace('company'));

/** ACL */
Zend_Registry::set('aclCompany', new Zend_Acl());

/** constants */
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('REQUEST_TIME', $_SERVER['REQUEST_TIME']);

define('DOCUMENT_CACHE', "../../cache/biz/");
define('USER_UID', Zend_Registry::get('sessCommon')->login['uid']);
define('USER_ROOT', DOCUMENT_ROOT.Commons::getUserFolder(USER_UID, '*'));
define('USER_CACHE', Commons::getUserCache(USER_ROOT));

/** run */
Zend_Controller_Front::getInstance()
    ->registerPlugin(new BizPreAjaxPlugin())
    ->registerPlugin(new BizAclPlugin())
	->setDefaultModule('biz')
    ->setControllerDirectory('../../application/biz/controllers/')
    ->throwExceptions(false)
    ->dispatch();
