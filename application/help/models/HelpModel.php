<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpModel.php
 */


/**
 * 你问我答
 * 公共操作类
 */
class HelpModel
{
    /**
     * dao
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
    	// 载入数据库类
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	// 断开数据库连接
    	$this->_dao->closeConnection();
    }

	/**
     * 全部最新
     * 
     * @param string $limit
     * @return array
     */
	public function latest($limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,s.pid,s.pName 
		    FROM tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE q.status = 0 AND q.sid = s.sid 
		    ORDER BY q.qid DESC LIMIT {$limit}", array());
    }

    /**
     * 全部高分
     * 
     * @param string $limit
     * @return array
     */
	public function offer($limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,s.pid,s.pName 
		    FROM tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE q.status = 0 AND q.sid = s.sid 
		    ORDER BY q.offer DESC LIMIT {$limit}", array());
    }

    /**
     * 全部被遗忘
     * 
     * @param string $limit
     * @return array
     */
	public function forget($limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,s.pid,s.pName 
		    FROM tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE q.status = 0 AND q.sid = s.sid 
		    ORDER BY q.reply ASC LIMIT {$limit}", array());
    }

    /**
     * 全部近解决
     * 
     * @param string $limit
     * @return array
     */
	public function solved($limit)
    {
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,s.pid,s.pName 
		    FROM tbl_ask_question AS q,tbl_ask_sort AS s 
		    WHERE q.status = 1 AND q.sid = s.sid 
		    ORDER BY q.qid DESC LIMIT {$limit}", array());   	
    }
}
