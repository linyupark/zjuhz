<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserModel.php
 */


/**
 * 会员中心-tbl_user
 * 表级操作类,含单表读/写/改等方法
 */
class UserModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_user';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'uid';

    /**
     * 数据表访问对象
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
    	// 载入数据库操作类
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	$this->_dao->closeConnection();
    }

    /**
     * 判断用户登录帐号是否已存在
     * 
     * @param string $userName
     * @return string
     */
	public function checkUserName($userName)
    {
    	return $this->_dao->fetchOne(
    	    "SELECT uid FROM {$this->_name} WHERE userName = :userName;",
            array('userName' => $userName)
        );
    }
}