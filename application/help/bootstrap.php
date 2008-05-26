<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:bootstrap.php
 */


/** set error_reporting */
error_reporting('ALL');

/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/help/interlayers/'.PATH_SEPARATOR.
                 '../../application/help/models/');

/** Zend_Loader */
require_once('Zend/Loader.php');
/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** Config */
Zend_Registry::set('iniDb', new Zend_Config_Ini('../../common/Ini/Db.ini'));
Zend_Registry::set('iniCommon', new Zend_Config_Ini('../../common/Ini/Config.ini'));
Zend_Registry::set('iniHelp', new Zend_Config_Ini('../../common/Ini/Help.ini'));

/** Session */
Zend_Registry::set('sessCommon', new Zend_Session_Namespace('common'));
Zend_Registry::set('sessHelp', new Zend_Session_Namespace('help'));

/** ACL */
Zend_Registry::set('aclHelp', new Zend_Acl());

/** run */
Zend_Controller_Front::getInstance()
    ->registerPlugin(new HelpPreAjaxPlugin())
    ->registerPlugin(new HelpAclPlugin(Zend_Registry::get('sessCommon')->role))
	->setDefaultModule('help')
    ->setControllerDirectory('../../application/help/controllers/')
    ->throwExceptions(false)
    ->dispatch();
