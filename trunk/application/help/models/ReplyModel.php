<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ReplyModel.php
 */


/**
 * 你问我答 - tbl_ask_reply
 * 表级操作类,含单表读/写/改等方法
 */
class ReplyModel //extends Zend_Db_Table_Abstract
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
     * 回答问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		$this->_dao->prepare('CALL sp_reply_insert(:qid,:uid,:content,:anonym,:offer,@rid);')
		           ->execute($args);

		return $this->_dao->query('SELECT @rid AS rid')->fetchColumn();
    }

    /**
     * 回答列表
     * 
     * @param integer $qid
     * @param string $limit
     * @return array
     */
	public function browse($qid, $limit)
    {
		return $this->_dao->fetchAll("SELECT ask.realName,r.* 
		    FROM tbl_ask AS ask,tbl_ask_reply AS r 
		    WHERE r.qid = :qid AND r.uid = ask.uid 
		    ORDER BY r.status DESC LIMIT {$limit};", 
            array('qid' => $qid));
    }
}
