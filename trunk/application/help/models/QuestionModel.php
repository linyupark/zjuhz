<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:QuestionModel.php
 */


/**
 * 你问我答 - tbl_ask_question
 * 表级操作类,含单表读/写/改等方法
 */
class QuestionModel //extends Zend_Db_Table_Abstract
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
     * 数据表访问对象
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
    	//载入数据库操作类
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
     * 全部待解决随机(适用于如广播)
     * 
     * @param string $limit
     * @return array
     */
	public function rand($limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title 
		    FROM {$this->_name} AS q 
		    WHERE q.status = 0 
		    ORDER BY RAND() LIMIT {$limit}", array());
    }

    /**
     * 提交问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		$this->_dao->prepare('CALL sp_question_insert(:uid,:title,:content,:tags,:sid,:offer,:anonym,@qid);')
		           ->execute($args);

		return $this->_dao->query('SELECT @qid AS qid')->fetchColumn();
    }

    /**
     * 采纳答案
     * 
     * @param array $args
     * @return integer
     */
	public function accept($args)
    {
		$this->_dao->prepare('CALL sp_question_accept(:rid,:qid,:quid,:ruid,@offer);')
		           ->execute($args);

		return $this->_dao->query('SELECT @offer AS offer')->fetchColumn();
    }

    /**
     * 显示问题详情
     * 
     * @param integer $qid
     * @return array
     */
	public function detail($qid)
    {
		return $this->_dao->fetchRow("SELECT q.*,ask.realName 
		    FROM {$this->_name} AS q,tbl_ask AS ask 
		    WHERE q.qid = :qid AND q.uid = ask.uid;", 
		    array('qid' => $qid));
    }

    /**
     * 列表自定义搜索
     * 
     * @param string $and
     * @param string $limit
     * @return array
     */
	public function selectSearchList($and, $limit)
    {
		return $this->_dao->fetchAll("SELECT q.*,a.realName,s.pid,s.pName 
		    FROM {$this->_name} AS q,tbl_ask AS a,tbl_ask_sort AS s 
		    WHERE q.status IN (0,1) AND q.uid = a.uid AND q.sid IN (s.sid,s.parent,s.pid) {$and} 
		    ORDER BY q.addTime DESC LIMIT {$limit};"
		);
    }

    /**
     * 列表自定义统计
     * 
     * @param string $and
     * @return integer
     */
	public function selectSearchCount($and)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} AS q,tbl_ask AS a,tbl_ask_sort AS s 
		    WHERE q.status IN (0,1) AND q.uid = a.uid AND q.sid IN (s.sid,s.parent,s.pid) {$and} ;" 
		);
    }
}
