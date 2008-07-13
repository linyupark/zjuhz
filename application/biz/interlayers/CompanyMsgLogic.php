<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CompanyMsgLogic.php
 */


/**
 * 校友企业-tbl_corp_company_msg
 * 控制器附属层:模型层操作入口
 */
class CompanyMsgLogic extends BizInterlayer
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

    	$this->_load('CompanyMsgModel');
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
     * 查找全部的留言内容
     * 
     * @param string $cid
     * @param string $limit
     * @return array
     */
	public function selectCidAll($cid, $limit)
	{
		return $this->_CompanyMsgModel->selectCidStatusAll($cid, '0, 1, 2', $limit);
	}

    /**
     * 查找未删除的留言内容
     * 
     * @param string $cid
     * @param string $limit
     * @return array
     */
	public function selectCidNotDeletedAll($cid, $limit)
	{
		return $this->_CompanyMsgModel->selectCidStatusAll($cid, '0, 1', $limit);
	}

    /**
     * 查找未回复的留言内容
     * 
     * @param string $cid
     * @param string $limit
     * @return array
     */
	public function selectCidNotReplyAll($cid, $limit)
	{
		return $this->_CompanyMsgModel->selectCidStatusAll($cid, '0', $limit);
	}

    /**
     * 查找已回复的留言内容
     * 
     * @param string $cid
     * @param string $limit
     * @return array
     */
	public function selectCidHasReplyAll($cid, $limit)
	{
		return $this->_CompanyMsgModel->selectCidStatusAll($cid, '1', $limit);
	}

    /**
     * 查找全部的留言内容
     * 
     * @param integer $uid
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $limit)
	{
		return $this->_CompanyMsgModel->selectUidStatusAll($uid, '0, 1, 2', $limit);
	}

    /**
     * 插入留言记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_CompanyMsgModel->insert($args);
	}

	/**
     * 更新留言的处理状态
     * 
     * @param array $args
     * @return integer
     */
	public function updateStatusDel($args)
	{
		$args['status'] = 2;

		return $this->_CompanyMsgModel->updateStatus($args);
	}

	/**
     * 更新留言的回复资料
     * 
     * @param array $args
     * @return integer
     */
	public function updateReply($args)
	{
		$args['status'] = 1;

		return $this->_CompanyMsgModel->updateReply($args);
	}
}
