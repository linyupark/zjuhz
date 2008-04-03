<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:RegisterLogic.php
 */


/**
 * 会员中心
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class RegisterLogic extends MemberInterlayer
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
     * 会员注册
     * 
     * @param array $input
     * @return numeric
     */
	public static function register($input)
	{
		$member = parent::_getInstance('MemberModel');

		return $member->register($input);
	}

	/**
     * 帐号检测
     * 
     * @param string $userName
     * @return boolean
     */
	public static function check($userName)
	{
		$user = parent::_getInstance('UserModel');

		return $user->checkUserName($userName);
	}
}
