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

/** ���������ĵ����� */
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));
/** ��Ŀ�����ĵ����� */
Zend_Registry::set('iniMember',new Zend_Config_Ini('../../common/Ini/Member.ini'));

Zend_Session::rememberMe(3600);
/** ����SESSION,��������֤��,�û��������ϵ� */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
/** ��ĿSESSION */
Zend_Registry::set('sessMember',new Zend_Session_Namespace('member'));

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');
