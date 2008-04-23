<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyLogic.php
 */


/**
 * 你问我答 - 会员后台专用类
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MyLogic extends HelpInterlayer
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

    	$this->_loadMdl('My');
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
     * 我的问题
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function question($uid, $status, $limit)
	{
		return $this->_mdlMy->question($uid, $status, $limit);
	}

	/**
     * 我的回答
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function reply($uid, $status, $limit)
	{
		return $this->_mdlMy->reply($uid, $status, $limit);
	}

	/**
     * 我的收藏
     * 
     * @param integer $uid
     * @param string $limit
     * @return array
     */
	public function collection($uid, $limit)
	{
		return $this->_mdlMy->collection($uid, $limit);
	}
}
