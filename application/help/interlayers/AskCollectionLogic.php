<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskCollectionLogic.php
 */


/**
 * 校友互助-tbl_ask_collection
 * 控制器附属层:模型层操作入口
 */
class AskCollectionLogic extends HelpInterlayer
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

    	$this->_load('AskCollectionModel');
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
     * 查找uid的收藏记录
     * 
     * @param integer $uid
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $limit)
	{
		return $this->_AskCollectionModel->selectUidAll($uid, $limit);
	}

    /**
     * 插入收藏记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_AskCollectionModel->insert($args);
	}
}
