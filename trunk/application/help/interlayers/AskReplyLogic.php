<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskReplyLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class AskReplyLogic extends HelpInterlayer
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

    	$this->_load('AskReplyModel');
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
     * 写入回复记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_AskReplyModel->callInsert($args);
    }

    /**
     * 查找qid的全部回复
     * 
     * @param integer $qid
     * @param string $limit
     * @return array
     */
	public function selectQidAll($qid, $limit)
    {
		return $this->_AskReplyModel->selectQidAll($qid, $limit);
    }

	/**
     * 查找uid按自定义状态的全部回复
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $status, $limit)
	{
		return $this->_AskReplyModel->selectUidAll($uid, $status, $limit);
	}
}
