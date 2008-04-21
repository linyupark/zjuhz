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

/** Zend_Loader */
require_once('Zend/Loader.php');
/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 项目配置文档对象 */
Zend_Registry::set('iniMember', new Zend_Config_Ini('../../common/Ini/Member.ini'));

/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon', new Zend_Session_Namespace('common'));
/** 项目SESSION */
Zend_Registry::set('sessMember', new Zend_Session_Namespace('member'));
/** 项目ACL */
Zend_Registry::set('aclMember', new Zend_Acl());

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');