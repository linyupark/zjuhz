<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskQuestionLogic.php
 */


/**
 * 校友互助
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
     * 插入问题资料
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_AskQuestionModel->callInsert($args);
    }

    /**
     * 更新问题数据(采纳答案)
     * 
     * @param array $args
     * @return integer
     */
	public function accept($args)
    {
		return $this->_AskQuestionModel->callAccept($args);
    }

    /**
     * 查找qid的问题详情
     * 
     * @param integer $qid
     * @return array
     */
	public function selectQidRow($qid)
    {
		return $this->_AskQuestionModel->selectQidRow($qid);
    }

	/**
     * 查找uid的问题记录
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $status, $limit)
	{
		return $this->_AskQuestionModel->selectUidAll($uid, $status, $limit);
	}

	/**
     * 查找随机问题
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
            "AND (q.title LIKE '%{$arg}%' OR q.content LIKE '%{$arg}%')" : '');

        return ('count' == $type ? 
            $this->_AskQuestionModel->selectSearchCount($and) : 
            $this->_AskQuestionModel->selectSearchList($and, $limit)
        );
    }

	/**
     * 查找全部最新提问
     * 
     * @param string $limit
     * @return array
     */
	public function selectLatestAll($limit)
	{
		return $this->_AskQuestionModel->selectQuestionAll(0, 'q.qid DESC', $limit);
	}

    /**
     * 查找全部高分提问
     * 
     * @param string $limit
     * @return array
     */
	public function selectOfferAll($limit)
	{
		return $this->_AskQuestionModel->selectQuestionAll(0, 'q.offer DESC', $limit);
	}

    /**
     * 查找全部被遗忘的
     * 
     * @param string $limit
     * @return array
     */
	public function selectForgetAll($limit)
	{
		return $this->_AskQuestionModel->selectQuestionAll(0, 'q.reply ASC', $limit);
	}

    /**
     * 查找全部最近解决
     * 
     * @param string $limit
     * @return array
     */
	public function selectSolvedAll($limit)
	{
		return $this->_AskQuestionModel->selectQuestionAll(1, 'q.qid DESC', $limit);
	}

	/**
     * 更新qid的问题资料
     * 
     * @param array $args
     * @param integer $qid
     * @return integer
     */
	public function update($args, $qid)
	{
		return $this->_AskQuestionModel->update($args, $qid);
	}
}
