<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskCollectionLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
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
     * 我的收藏
     * 
     * @param integer $uid
     * @param string $limit
     * @return array
     */
	public function selectMyList($uid, $limit)
	{
		return $this->_AskCollectionModel->selectMyList($uid, $limit);
	}

	/**
     * 收藏问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
	{
		return $this->_AskCollectionModel->insert($args);
	}
}
