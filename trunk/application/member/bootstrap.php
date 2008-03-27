<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:bootstrap.php
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/member/interlayers/'.PATH_SEPARATOR.
                 '../../application/member/models/');
//echo get_include_path();exit;

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 公用配置文档对象 */
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));
/** 项目配置文档对象 */
Zend_Registry::set('iniMember',new Zend_Config_Ini('../../common/Ini/Member.ini'));

Zend_Session::rememberMe(3600);
/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
/** 项目SESSION */
Zend_Registry::set('sessMember',new Zend_Session_Namespace('member'));

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');
