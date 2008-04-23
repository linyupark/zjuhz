<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SortLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class SortLogic extends HelpInterlayer
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

    	$this->_loadMdl('Sort');
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
     * 显示分类列表
     * 
     * @param integer $parent
     * @return array
     */
	public function fetchPairs($parent)
	{
		return $this->_mdlSort->fetchPairs($parent);
	}

	/**
     * 显示分类列表
     * 
     * @return array
     */
	public function fetchAll()
	{
		return $this->_mdlSort->fetchAll();
	}

    /**
     * 分类下属问题计数器
     * 
     * @param array $args
     * @return integer
     */
	public function counter($args)
    {
		return $this->_mdlSort->counter($args);
    }

	/**
     * 按分类显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function browse($sid, $limit)
	{
		return $this->_mdlSort->browse($sid, $limit);
	}

	/**
     * 按最新显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function latest($sid, $limit)
	{
		return $this->_mdlSort->latest($sid, $limit);
	}

	/**
     * 按高分显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function offer($sid, $limit)
	{
		return $this->_mdlSort->offer($sid, $limit);
	}

	/**
     * 按回复显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function forget($sid, $limit)
	{
		return $this->_mdlSort->forget($sid, $limit);
	}

	/**
     * 按解决显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function solved($sid, $limit)
	{
		return $this->_mdlSort->solved($sid, $limit);
	}
}
