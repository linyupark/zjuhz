<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpLogic.php
 */


/**
 * 校友企业-tbl_corp
 * 控制器附属层:模型层操作入口
 */
class CorpLogic extends CompanyInterlayer
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

    	$this->_load('CorpModel');
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
     * 查找uid的模块资料
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidRow($uid)
	{
		return $this->_CorpModel->selectUidRow($uid);
	}

    /**
     * 插入uid的模块记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_CorpModel->insert($args);
	}

	/**
     * 更新uid的模块资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
	{
		return $this->_CorpModel->update($args);
	}
}
