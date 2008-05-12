<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class AskLogic extends HelpInterlayer
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

    	$this->_load('AskModel');
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
     * 单个会员的模块资料
     * 
     * @param integer $uid
     * @return array
     */
	public function selectRow($uid)
	{
		return $this->_AskModel->selectRow($uid);
	}

	/**
     * 激活tbl_ask表
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_AskModel->insert($args);
	}

	/**
     * 更新表资料
     * 
     * @param array $args
     * @param integer $uid
     * @return array
     */
	public function update($args, $uid)
	{
		return $this->_AskModel->update($args, $uid);
	}
}
