<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : index.php $
 * $Author : wangyumin $
 */


/** set_include_path */
set_include_path(get_include_path().PATH_SEPARATOR.'../../common/Custom/'.PATH_SEPARATOR.'../../application/member/models/');

/** Zend_Controller_Front */
require_once 'Zend/Controller/Front.php';

/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

/** Zend_Registry */
//$registry = Zend_Registry::getInstance();

/** Zend_Config_Ini */
$config = new Zend_Config_Ini('../../common/Ini/Member.ini', 'language');
Zend_Registry::set('config', $config);

// Automatically load class Zend_Db_Adapter_Pdo_Mysql and create an instance of it.
$db = Zend_Db::factory('Pdo_Mysql',array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => '123456',
    'dbname'   => 'zjuhz_user'
));

/** registry db */
Zend_Registry::set('db', $db);

//Zend_Db_Table::setDefaultAdapter($db);

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');
