<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : index.php $
 * $Author : wangyumin $
 */


/** zjuhz common */

require_once 'Zend/Controller/Front.php';

/** Zend_Loder */
//require_once('Zend/Loader.php');
/** Zend_Loader autoloader callback */
Zend_Loader::registerAutoload();

// Automatically load class Zend_Db_Adapter_Pdo_Mysql and create an instance of it.
$db = Zend_Db::factory('Pdo_Mysql',array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => '123456',
    'dbname'   => 'zjuhz_user'
));

//register db
Zend_Registry::set('db', $db);

//
Zend_Db_Table::setDefaultAdapter($db);

/** run */
Zend_Controller_Front::run('../../application/member/controllers/');
