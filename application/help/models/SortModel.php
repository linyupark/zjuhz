<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SortModel.php
 */


/**
 * 你问我答 - tbl_ask_sort
 * 表级操作类,含单表读/写/改等方法
 */
class SortModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */	
    protected $_name = 'tbl_ask_sort';

    /**
     * 数据表主键
     * @var string
     */    
    protected $_primary = 'sid';

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
     * 显示分类列表 Pairs-style
     * 
     * @param integer $parent
     * @return array
     */
	public function fetchPairs($parent)
    {
		return $this->_dao->fetchPairs("SELECT sid,name FROM {$this->_name} WHERE parent = :parent;", 
		    array('parent' => $parent));
    }

    /**
     * 显示分类列表 All-style
     * 
     * @return array
     */
	public function fetchAll()
    {
		return $this->_dao->fetchAll("SELECT * FROM {$this->_name};", array());
    }

    /**
     * 分类下属问题计数器
     * 
     * @param array $args
     * @return integer
     */
	public function counter($args)
	{
		return $this->_dao->prepare('CALL sp_sort_counter(:sort0,:sort1,:sort2,:filed);')
		                  ->execute($args);
	}

	/**
     * 按分类显示问题列表
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function browse($sid, $limit)
	{
		return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.addTime,q.status,q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status = 0 OR q.status = 1) AND q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent) 
		    ORDER BY q.qid DESC LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid));
    }

	/**
     * 按分类显示最新求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function latest($sid, $limit)
    {
    	return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.addTime,q.status,q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status = 0) AND q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent) 
		    ORDER BY q.qid DESC LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid));
    }

    /**
     * 按分类显示高分求助
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function offer($sid, $limit)
    {
    	return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.addTime,q.status,q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status = 0) AND q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent) 
		    ORDER BY q.offer DESC LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid));
    }

    /**
     * 按分类显示被遗忘的
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function forget($sid, $limit)
    {
    	return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.addTime,q.status,q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status = 0) AND q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent) 
		    ORDER BY q.reply ASC LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid));
    }

    /**
     * 按分类显示最近解决
     * 
     * @param integer $sid
     * @param string $limit
     * @return array
     */
	public function solved($sid, $limit)
    {
    	return $this->_dao->fetchAll("SELECT q.qid,q.title,q.offer,q.addTime,q.status,q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status = 1) AND q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent) 
		    ORDER BY q.qid DESC LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid));
    }
}
