<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:BaseLogic.php
 */


/**
 * 校友企业-tbl_base
 * 控制器附属层:模型层操作入口
 */
class BaseLogic extends BizInterlayer
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

    	$this->_load('BaseModel');
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
     * 查找基础数据资料
     * 
     * @return array
     */
	public function selectRow()
	{
		return $this->_BaseModel->selectRow();
	}

	/**
     * 更新基础数据资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
	{
		return $this->_BaseModel->update($args);
	}
}
