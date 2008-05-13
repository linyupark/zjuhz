<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskQuestionModel.php
 */


/**
 * 你问我答-tbl_ask_question
 * 表级操作类,含单表读/写/改等方法
 */
class AskQuestionModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_ask_question';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'qid';

    /**
     * 数据表访问
     * @var object
     */
    protected $_dao = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	$this->_dao->closeConnection();
    }

    /**
     * 插入问题资料
     * 
     * @param array $args
     * @return integer
     */
	public function callInsert($args)
    {
		$this->_dao->prepare('CALL sp_question_insert(:uid, :title, :content, :tags, :sid, :offer, :anonym, @qid);')
		           ->execute($args);

		return $this->_dao->query('SELECT @qid AS qid')->fetchColumn();
    }

    /**
     * 更新问题数据(采纳答案)
     * 
     * @param array $args
     * @return integer
     */
	public function callAccept($args)
    {
		$this->_dao->prepare('CALL sp_question_accept(:rid, :qid, :quid, :ruid, @offer);')
		           ->execute($args);

		return $this->_dao->query('SELECT @offer AS offer')->fetchColumn();
    }

    /**
     * 查找qid的问题详情
     * 
     * @param integer $qid
     * @return array
     */
	public function selectQidRow($qid)
    {
		return $this->_dao->fetchRow("SELECT q.*, ask.realName 
		    FROM {$this->_name} AS q, tbl_ask AS ask 
		    WHERE q.qid = :qid AND q.uid = ask.uid;", array('qid' => $qid)
		);
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
		return $this->_dao->fetchAll("SELECT q.qid, q.title, q.offer, q.anonym, q.addTime, q.replyTime, q.reply, s.pid, s.pName 
		    FROM tbl_ask_question AS q, tbl_ask_sort AS s 
		    WHERE q.uid = :uid AND q.status = :status AND q.sid = s.sid 
		    ORDER BY q.replyTime DESC LIMIT {$limit}", array('uid' => $uid, 'status' => $status)
		);
    }

	/**
     * 查找随机问题或答案
     * 
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function selectRandList($status, $limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid, q.title 
		    FROM {$this->_name} AS q 
		    WHERE q.status = :status 
		    ORDER BY RAND() LIMIT {$limit};", array('status' => $status)
		);
    }

    /**
     * 搜索自定义方式的记录
     * 
     * @param string $and
     * @param string $limit
     * @return array
     */
	public function selectSearchList($and, $limit)
    {
		return $this->_dao->fetchAll("SELECT q.*, a.realName, s.pid, s.pName 
		    FROM {$this->_name} AS q, tbl_ask AS a, tbl_ask_sort AS s 
		    WHERE q.status IN (0,1) AND q.uid = a.uid AND q.sid IN (s.sid, s.parent, s.pid) {$and} 
		    ORDER BY q.addTime DESC LIMIT {$limit};"
		);
    }

    /**
     * 统计自定义搜索的记录
     * 
     * @param string $and
     * @return integer
     */
	public function selectSearchCount($and)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} AS q, tbl_ask AS a, tbl_ask_sort AS s 
		    WHERE q.status IN (0,1) AND q.uid = a.uid AND q.sid IN (s.sid, s.parent, s.pid) {$and};" 
		);
    }

	/**
     * 查找自定义状态的全部问题
     * 
     * @param integer $status
     * @param string $order
     * @param string $limit
     * @return array
     */
	public function selectQuestionAll($status, $order, $limit)
    {
		return $this->_dao->fetchAll("SELECT q.*, s.pid, s.pName 
		    FROM tbl_ask_question AS q, tbl_ask_sort AS s 
		    WHERE q.status = :status AND q.sid = s.sid 
		    ORDER BY {$order} LIMIT {$limit};", array('status' => $status)
		);
    }
}
