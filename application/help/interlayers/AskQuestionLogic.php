<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskQuestionLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class AskQuestionLogic extends HelpInterlayer
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

    	$this->_load('AskQuestionModel');
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
     * 提交问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_AskQuestionModel->callInsert($args);
    }

    /**
     * 采纳答案
     * 
     * @param array $args
     * @return integer
     */
	public function accept($args)
    {
		return $this->_AskQuestionModel->callAccept($args);
    }

    /**
     * 显示问题详情
     * 
     * @param integer $qid
     * @return array
     */
	public function selectRow($qid)
    {
		return $this->_AskQuestionModel->selectRow($qid);
    }

	/**
     * 我的问题
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function selectMyList($uid, $status, $limit)
	{
		return $this->_AskQuestionModel->selectMyList($uid, $status, $limit);
	}

	/**
     * 随机显示问题
     * 
     * @param string $limit
     * @return array
     */
	public function selectRandQuestion($limit)
	{
		return $this->_AskQuestionModel->selectRandList(0, $limit);
	}

    /**
     * 问题/答案/标签搜索
     * 
     * @param string $type
     * @param string $arg
     * @param string $limit
     * @return integer or array
     */
	public function selectSearch($type, $arg, $limit)
    {
    	$and = (!empty($arg) ? 
            "AND (q.title LIKE '%{$arg}%' OR q.content LIKE '%{$arg}%' OR q.tags LIKE '%{$arg}%')" : '');

        return ('count' == $type ? 
            $this->_AskQuestionModel->selectSearchCount($and) : 
            $this->_AskQuestionModel->selectSearchList($and, $limit)
        );
    }

    /**
     * 最新求助
     * 
     * @param string $limit
     * @return array
     */
	public function selectAllLatest($limit)
	{
		return $this->_AskQuestionModel->selectAllLatest($limit);
	}

    /**
     * 高分悬赏
     * 
     * @param string $limit
     * @return array
     */
	public function selectAllOffer($limit)
	{
		return $this->_AskQuestionModel->selectAllOffer($limit);
	}

    /**
     * 被遗忘的
     * 
     * @param string $limit
     * @return array
     */
	public function selectAllForget($limit)
	{
		return $this->_AskQuestionModel->selectAllForget($limit);
	}

    /**
     * 最近解决
     * 
     * @param string $limit
     * @return array
     */
	public function selectAllSolved($limit)
	{
		return $this->_AskQuestionModel->selectAllSolved($limit);
	}
}
