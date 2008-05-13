<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskReplyModel.php
 */


/**
 * 你问我答-tbl_ask_reply
 * 表级操作类,含单表读/写/改等方法
 */
class AskReplyModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_ask_reply';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'rid';

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
     * 写入回复记录
     * 
     * @param array $args
     * @return integer
     */
	public function callInsert($args)
    {
		$this->_dao->prepare('CALL sp_reply_insert(:qid, :uid, :content, :anonym, :offer, @rid);')
		           ->execute($args);

		return $this->_dao->query('SELECT @rid AS rid')->fetchColumn();
    }

    /**
     * 查找qid的全部回复
     * 
     * @param integer $qid
     * @param string $limit
     * @return array
     */
	public function selectQidAll($qid, $limit)
    {
		return $this->_dao->fetchAll("SELECT ask.realName, r.* 
		    FROM tbl_ask AS ask, tbl_ask_reply AS r 
		    WHERE r.qid = :qid AND r.uid = ask.uid 
		    ORDER BY r.status DESC LIMIT {$limit};", array('qid' => $qid)
        );
    }

	/**
     * 查找uid按自定义状态的全部回复
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $status, $limit)
    {
		return $this->_dao->fetchAll("SELECT r.rid, r.uid, r.anonym, r.addTime, q.qid, q.title, q.offer, q.reply, s.pid, s.pName 
		    FROM tbl_ask_reply AS r, tbl_ask_question AS q, tbl_ask_sort AS s 
		    WHERE (r.uid = :uid) AND (r.status = 1 OR r.status = :status) AND r.qid = q.qid AND q.sid = s.sid 
		    ORDER BY r.addTime DESC LIMIT {$limit};", array('uid' => $uid, 'status' => $status)
		);
    }
}
