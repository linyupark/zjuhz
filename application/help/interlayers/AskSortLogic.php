<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskSortLogic.php
 */


/**
 * 校友互助-tbl_ask_sort
 * 控制器附属层:模型层操作入口
 */
class AskSortLogic extends HelpInterlayer
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

    	$this->_load('AskSortModel');
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
     * 更新分类拥有的数量
     * 
     * @param array $args
     * @return void
     */
	public function counter($args)
    {
		$this->_AskSortModel->callCounter($args);
    }

    /**
     * 查找全部分类
     * 
     * @return array
     */
	public function selectAll()
	{
		return $this->_AskSortModel->selectAll();
	}

    /**
     * 查找parent的子分类
     * 
     * @param integer $parent
     * @return array
     */
	public function selectParentPairs($parent)
	{
		return $this->_AskSortModel->selectParentPairs($parent);
	}

	/**
     * 查找按分类自定义状态的全部问题
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectQuestionAll($sid, $limit)
	{
		return $this->_AskSortModel->selectQuestionAll('0, 1', $sid, 'q.qid DESC', $limit);
	}

	/**
     * 查找按分类自定义状态的最新求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectLatestAll($sid, $limit)
	{
		return $this->_AskSortModel->selectQuestionAll('0', $sid, 'q.qid DESC', $limit);
	}

	/**
     * 查找按分类自定义状态的高分求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectOfferAll($sid, $limit)
	{
		return $this->_AskSortModel->selectQuestionAll('0', $sid, 'q.offer DESC', $limit);
	}

	/**
     * 查找按分类自定义状态的被遗忘的
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectForgetAll($sid, $limit)
	{
		return $this->_AskSortModel->selectQuestionAll('0', $sid, 'q.reply ASC', $limit);
	}

	/**
     * 查找按分类自定义状态的最近解决
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSolvedAll($sid, $limit)
	{
		return $this->_AskSortModel->selectQuestionAll('1', $sid, 'q.qid DESC', $limit);
	}
}
