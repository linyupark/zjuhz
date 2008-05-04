<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:QuestionLogic.php
 */


/**
 * 你问我答
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class QuestionLogic extends HelpInterlayer
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

    	$this->_loadMdl('Question');
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
     * 登录子系统
     * 
     * @param string $limit
     * @return array
     */
	public function rand($limit)
	{
		return $this->_mdlQuestion->rand($limit);
	}

    /**
     * 提交问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_mdlQuestion->insert($args);
    }

    /**
     * 采纳答案
     * 
     * @param array $args
     * @return integer
     */
	public function accept($args)
    {
		return $this->_mdlQuestion->accept($args);
    }

    /**
     * 显示问题详情
     * 
     * @param integer $qid
     * @return array
     */
	public function detail($qid)
    {
		return $this->_mdlQuestion->detail($qid);
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
    	if (!empty($arg))
    	{
    		$and = "AND (q.title LIKE '%{$arg}%' OR q.content LIKE '%{$arg}%' OR q.tags LIKE '%{$arg}%')";
    	}

        return ('count' == $type ? 
            $this->_mdlQuestion->selectSearchCount($and) : 
            $this->_mdlQuestion->selectSearchList($and, $limit)
        );
    }
}
