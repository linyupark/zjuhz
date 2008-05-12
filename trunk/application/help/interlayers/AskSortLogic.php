<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskSortLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
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
     * 分类下属问题计数器
     * 
     * @param array $args
     * @return integer
     */
	public function counter($args)
    {
		return $this->_AskSortModel->callCounter($args);
    }

	/**
     * 显示分类列表
     * 
     * @return array
     */
	public function selectList()
	{
		return $this->_AskSortModel->selectList();
	}

	/**
     * 显示分类列表
     * 
     * @param integer $parent
     * @return array
     */
	public function selectParentList($parent)
	{
		return $this->_AskSortModel->selectParentList($parent);
	}

	/**
     * 按分类显示全部问题
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSortAll($sid, $limit)
	{
		return $this->_AskSortModel->selectSortAll($sid, $limit);
	}

	/**
     * 按分类显示最新求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSortLatest($sid, $limit)
	{
		return $this->_AskSortModel->selectSortLatest($sid, $limit);
	}

	/**
     * 按分类显示高分求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSortOffer($sid, $limit)
	{
		return $this->_AskSortModel->selectSortOffer($sid, $limit);
	}

	/**
     * 按分类显示被遗忘的
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSortForget($sid, $limit)
	{
		return $this->_AskSortModel->selectSortForget($sid, $limit);
	}

	/**
     * 按分类显示最近解决
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function selectSortSolved($sid, $limit)
	{
		return $this->_AskSortModel->selectSortSolved($sid, $limit);
	}
}
