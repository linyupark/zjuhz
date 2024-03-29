<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserLogic.php
 */


/**
 * 校友中心-tbl_user
 * 控制器附属层:模型层操作入口
 */
class UserLogic extends MemberInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	parent::_initLogic();

    	$this->_load('UserModel');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	parent::__destruct();
    }

    /**
     * 类实例
     * 
     * @return object
     */
	public static function init()
    {
    	return parent::_getInstance(__CLASS__);
    }

	/**
     * 会员账号注册
     * 
     * @param array $args
     * @return integer
     */
	public function register($args)
	{
		return $this->_UserModel->callRegister($args);
	}

    /**
     * 会员账号登录
     * 
     * @param array $args
     * @return array or boolean
     */
	public function login($args)
	{
		$row = $this->_UserModel->callLogin($args);

		return ($row['uid'] ? $row : false);
	}

    /**
     * 查找username存在数量
     * 
     * @param string $username
     * @return integer
     */
	public function selectUsernameCount($username)
	{
		return $this->_UserModel->selectFieldCount('username', $username);
	}

    /**
     * 更新会员基本资料
     * 
     * @param array $args
     * @param integer $uid
     * @return integer
     */
	public function update($args, $uid)
	{
		return $this->_UserModel->update($args, $uid);
	}

    /**
     * 更新会员密码
     * 
     * @param array $args
     * @return integer
     */
	public function updatePassword($args)
	{
		return $this->_UserModel->updatePassword($args);
	}
}
