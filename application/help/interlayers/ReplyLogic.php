<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ReplyLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class ReplyLogic extends HelpInterlayer
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

    	$this->_loadMdl('Reply');
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
     * 回答问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_mdlReply->insert($args);
    }

    /**
     * 回答列表
     * 
     * @param integer $qid
     * @param string $limit
     * @return array
     */
	public function browse($qid, $limit)
    {
		return $this->_mdlReply->browse($qid, $limit);
    }
}