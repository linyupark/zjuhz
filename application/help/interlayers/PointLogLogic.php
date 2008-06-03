<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:PointLogLogic.php
 */


/**
 * 校友互助-tbl_ask_point_log
 * 控制器附属层:模型层操作入口
 */
class PointLogLogic extends HelpInterlayer
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

    	$this->_load('PointLogModel');
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
     * 插入积分日志记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_PointLogModel->insert($args);
	}
}
