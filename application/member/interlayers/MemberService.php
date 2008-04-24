<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberService.php
 */


/**
 * 会员中心_XML-RPC接口开放类
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MemberService extends MemberInterlayer
{
	/**
     * 构造方法
     * 
     * @return void
     */
	public function __construct()
	{
		parent::__construct();
		parent::_initService();
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
     * 会员登录
     * $args = array('username' => '', 'password' => '', 'lastIp' => '');
     * 
     * @param struct $args
     * @return array or false
     */
	public function memberLogin($args)
	{
		return LoginLogic::init()->login($args);
	}

	/**
     * 更新表数据
     * $args = array('status' => xxx, 'editNick' => xxx, 'initAsk' => xxx);
     * 
     * @param struct $args
     * @param integer $uid
     * @return integer
     */
	public function extUpdate($args, $uid)
	{
		return MemberLogic::init()->extUpdate($args, $uid);
	}
}
