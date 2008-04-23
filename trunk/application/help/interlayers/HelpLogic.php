<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class HelpLogic extends HelpInterlayer
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

    	$this->_loadMdl('Help');
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
     * 最新求助
     * 
     * @param string $limit
     * @return array
     */
	public function latest($limit)
	{
		return $this->_mdlHelp->latest($limit);
	}

    /**
     * 高分悬赏
     * 
     * @param string $limit
     * @return array
     */
	public function offer($limit)
	{
		return $this->_mdlHelp->offer($limit);
	}

    /**
     * 被遗忘的
     * 
     * @param string $limit
     * @return array
     */
	public function forget($limit)
	{
		return $this->_mdlHelp->forget($limit);
	}

    /**
     * 最近解决
     * 
     * @param string $limit
     * @return array
     */
	public function solved($limit)
	{
		return $this->_mdlHelp->solved($limit);
	}
}
