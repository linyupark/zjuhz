<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskCollectionModel.php
 */


/**
 * 校友互助-tbl_ask_collection
 * 表级操作类,含单表读/写/改等方法
 */
class AskCollectionModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_ask_collection';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'cid';

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
     * 查找uid的收藏记录
     * 
     * @param integer $uid
     * @param string $limit
     * @return array
     */
	public function selectUidAll($uid, $limit)
    {
		return $this->_dao->fetchAll("SELECT c.cid, c.addTime, q.qid, q.title, q.anonym, q.offer, q.reply, s.pid, s.pName 
		    FROM tbl_ask_collection AS c, tbl_ask_question AS q, tbl_ask_sort AS s 
		    WHERE c.uid = :uid AND c.qid = q.qid AND q.sid = s.sid 
		    ORDER BY c.addTime DESC LIMIT {$limit}", array('uid' => $uid)
		);
    }

    /**
     * 插入收藏记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }
}
