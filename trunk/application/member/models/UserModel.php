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
class UserModel
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
     * 会员注册
     * 
     * @param array $args
     * @return integer
     */
	public function callRegister($args)
    {
		$this->_dao->prepare('CALL sp_register
		    (:username, :password, :realName, :sex, :regIp, :ikey, @uid);')
		    ->execute($args);

		return $this->_dao->query('SELECT @uid AS uid')->fetchColumn();
    }

    /**
     * 会员登录
     * 
     * @param array $args
     * @return array
     */
	public function callLogin($args)
    {
		$stmt = $this->_dao->prepare('CALL sp_login(:username, :password, :lastIp);');
		$stmt->execute($args);

		return $stmt->fetch();
    }

    /**
     * 登录帐号存在与否
     * 
     * @param string $username
     * @return integer
     */
	public function selectUsernameExist($username)
    {
    	return $this->_dao->fetchOne("SELECT COUNT(*) FROM {$this->_name} 
    	    WHERE username = :username;", array('username' => $username)
        );
    }

    /**
     * 昵称存在与否
     * 
     * @param string $nickname
     * @return integer
     */
	public function selectNicknameExist($nickname)
    {
    	return $this->_dao->fetchOne("SELECT COUNT(*) FROM {$this->_name} 
		    WHERE nickname = :nickname;", array('nickname' => $nickname)
        );
    }

    /**
     * 常规更新数据
     * 
     * @param array $args
     * @param integer $uid
     * @return integer
     */
	public function update($args, $uid)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $uid)
		);
    }
}
