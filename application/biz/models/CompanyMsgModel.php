<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CompanyMsgModel.php
 */


/**
 * 校友企业-tbl_corp_company_msg
 * 表级操作类,含单表读/写/改等方法
 */
class CompanyMsgModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp_company_msg';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'mid';

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
     * 查找cid按status的全部企业
     * 
     * @param string $cid
     * @param string $status
     * @param string $limit
     * @return array
     */
	public function selectCidStatusAll($cid, $status, $limit)
    {
		return $this->_dao->fetchAll("SELECT msg.*, corp.realName FROM {$this->_name} AS msg, tbl_corp AS corp 
		    WHERE msg.cid = :cid AND msg.status IN ({$status}) AND msg.uid = corp.uid 
		    ORDER BY msg.mid DESC LIMIT {$limit};", 
		    array('cid' => $cid)
	    );
    }

    /**
     * 查找uid按status的全部企业
     * 
     * @param integer $uid
     * @param string $status
     * @param string $limit
     * @return array
     */
	public function selectUidStatusAll($uid, $status, $limit)
    {
		return $this->_dao->fetchAll("SELECT msg.*, company.name FROM {$this->_name} AS msg, tbl_corp_company AS company 
		    WHERE msg.uid = :uid AND msg.status IN ({$status}) AND msg.cid = company.cid 
		    ORDER BY msg.mid DESC LIMIT {$limit};", 
		    array('uid' => $uid)
	    );
    }

    /**
     * 插入留言记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

	/**
     * 更新留言资料的状态
     * 
     * @param array $args
     * @return integer
     */
	public function updateStatus($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET status = :status 
    	    WHERE mid = :mid AND cid = :cid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }

	/**
     * 更新留言的回复资料
     * 
     * @param array $args
     * @return integer
     */
	public function updateReply($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET reply = :reply, status = :status 
    	    WHERE mid = :mid AND cid = :cid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
