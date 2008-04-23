<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyModel.php
 */


/**
 * 你问我答 - 会员后台专用类
 * 表级操作类,含单表读/写/改等方法
 */
class MyModel //extends Zend_Db_Table_Abstract
{
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
     * 我的问题
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $limit
     * @return array
     */
	public function question($uid, $status, $limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.anonym,q.addTime,q.replyTime,q.reply,s.pid,s.pName 
		    FROM tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE q.uid =:uid AND q.status =:status AND q.sid = s.sid 
		    ORDER BY q.replyTime DESC LIMIT {$limit}", array('uid' => $uid, 'status' => $status));
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
		return $this->_dao->fetchAll("SELECT r.rid,r.uid,r.anonym,r.addTime,q.qid,q.title,q.offer,q.reply,s.pid,s.pName 
		    FROM tbl_ask_reply AS r,tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE r.uid =:uid AND (r.status = 1 OR r.status =:status) AND r.qid = q.qid AND q.sid = s.sid 
		    ORDER BY r.addTime DESC LIMIT {$limit}", array('uid' => $uid, 'status' => $status));
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
		return $this->_dao->fetchAll("SELECT c.cid,c.addTime,q.qid,q.title,q.anonym,q.offer,q.reply,s.pid,s.pName 
		    FROM tbl_ask_collection AS c,tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE c.uid =:uid AND c.qid = q.qid AND q.sid = s.sid 
		    ORDER BY c.addTime DESC LIMIT {$limit}", array('uid' => $uid));
    }
}
