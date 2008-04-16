<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberModel.php
 */


/**
 * 会员中心
 * 库级操作类,含存储过程/多表操作/跨库操作等
 */
class MemberModel
{
    /**
     * dao
     * @var object
     */    
    protected $_dao = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	// 载入数据库类
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	// 断开数据库连接
    	$this->_dao->closeConnection();
    }

    /**
     * 会员注册
     * 
     * @param array $args
     * @return string
     */
	public function register($args)
    {
		$stmt = $this->_dao->prepare('CALL sp_register(:userName,:passWord,:realName,:sex,:regIp,:ikey,@uid);');
		$stmt->execute($args);

		return $this->_dao->query('SELECT @uid AS uid')->fetchColumn();
    }

    /**
     * 会员登录
     * 
     * @param array $args
     * @return array
     */
	public function login($args)
    {
		$stmt = $this->_dao->prepare('CALL sp_login(:userName,:passWord,:lastIp);');
		$stmt->execute($args);

		return $stmt->fetch();
    }
}
