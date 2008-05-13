<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberService.php
 */


/**
 * 会员中心-XMLRPC接口类
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
		//parent::__construct();
	}

	/**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
	{
		//parent::__destruct();
	}

    /**
     * 常规更新数据
     * 
     * @param struct $args
     * @param integer $uid
     * @return integer
     */
	public function UserExtUpdate($args, $uid)
	{
		return UserExtLogic::init()->update($args, $uid);
	}

    /**
     * 根据会员ID返回对应的通讯录组列表
     * 
     * @param integer $uid
     * @return array
     */
	public function AddressGroupSelectUidAll($uid)
	{
		return AddressGroupLogic::init()->selectUidAll($uid);
	}

    /**
     * 根据会员ID和组ID返回对应的名片列表
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function AddressCardSelectGidAll($gid, $uid)
	{
		return AddressCardLogic::init()->selectGidAll($gid, $uid);
	}
}
