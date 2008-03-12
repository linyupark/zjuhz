<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : index.php $
 * $Author : wangyumin $
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.
                 '../../common/Custom/'.PATH_SEPARATOR.
                 '../../application/member/models/');

/** Zend_Controller_Front */
require_once('Zend/Controller/Front.php');

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** 载入配置文档 */
$ini = new Zend_Config_Ini('../../common/Ini/Member.ini');

/** Registry 配置文档对象 */
Zend_Registry::set('ini',$ini);
/** Registry 数据库 */
$db = Zend_Db::factory($ini->db->default->adapter,$ini->db->default->params->toArray());
$db->query('SET NAMES utf8');
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db',$db);

/** 公共SESSION,包含如验证码,用户基本资料等 */
Zend_Registry::set('sessCommon',new Zend_Session_Namespace('common'));
/** 项目专用SESSION */
Zend_Registry::set('sessMember',new Zend_Session_Namespace('member'));

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');
