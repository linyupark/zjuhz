<?php

/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/member/interlayers/'.PATH_SEPARATOR.
                 '../../application/member/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 公用配置文档对象 */
Zend_Registry::set('iniConfig',new Zend_Config_Ini('../../common/Ini/Config.ini'));

/** 公用SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));

/** run */
Zend_Controller_Front::run('../../application/temp/controllers/');
