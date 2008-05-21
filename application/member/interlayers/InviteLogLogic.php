<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:InviteLogLogic.php
 */


/**
 * 校友中心-通讯录-邀请日志
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class InviteLogLogic extends MemberInterlayer
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

    	$this->_load('InviteLogModel');
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
     * 查询cid对应的邀请日志详情
     * 
     * @param string $cid
     * @return array
     */
	public function selectCidRow($cid)
    {
    	return $this->_InviteLogModel->selectCidRow($cid);
    }

    /**
     * 插入邀请日志记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_InviteLogModel->insert($args);
    }

    /**
     * 更新邀请日志资料
     * 
     * @param array $args
     * @param string $cid
     * @return integer
     */
	public function update($args, $cid)
    {
		return $this->_InviteLogModel->update($args, $cid);
    }
}
