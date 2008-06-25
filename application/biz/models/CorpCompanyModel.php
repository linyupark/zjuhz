<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpCompanyModel.php
 */


/**
 * 校友企业-tbl_corp_company
 * 表级操作类,含单表读/写/改等方法
 */
class CorpCompanyModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp_company';

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
     * 初始化企业资料
     * 
     * @param array $args
     * @return boolean
     */
	public function callInsert($args)
    {
		$this->_dao->prepare('CALL sp_company_insert(:cid, :uid, :name, :industry, 
		    :property, :province, :city, :intro, :phone);')->execute($args);

		return true;
    }

    /**
     * 查找cid的详细资料
     * 
     * @param string $cid
     * @return array
     */
	public function selectCidRow($cid)
    {
    	return $this->_dao->fetchRow("SELECT * FROM {$this->_name} AS company, 
    	    tbl_corp_company_biz AS biz, tbl_corp_company_contact AS contact 
    	    WHERE company.cid = :cid AND company.cid = biz.cid AND company.cid = contact.cid;", 
    	    array('cid' => $cid)
    	);
    }

    /**
     * 查找cid和uid的详细资料
     * 
     * @param string $cid
     * @param integer $uid
     * @return array
     */
	public function selectCidUidRow($cid, $uid)
    {
    	return $this->_dao->fetchRow("SELECT * FROM {$this->_name} AS company, 
    	    tbl_corp_company_biz AS biz, tbl_corp_company_contact AS contact 
    	    WHERE company.cid = :cid AND company.uid = :uid AND company.cid = biz.cid AND company.cid = contact.cid;", 
    	    array('cid' => $cid, 'uid' => $uid)
    	);
    }

    /**
     * 查找不分类的随机推荐企业
     * 
     * @param string $limit
     * @return array
     */
	public function selectRandList($limit)
    {
		return $this->_dao->fetchAll("SELECT * FROM tbl_corp_company 
		    WHERE status = 1 AND recmd = 1 ORDER BY rand() LIMIT {$limit};", array()
		);
    }

    /**
     * 查找分类的随机推荐企业
     * 
     * @param integer $industry
     * @param string $limit
     * @return array
     */
	public function selectIndustryRandList($industry, $limit)
    {
		return $this->_dao->fetchAll("SELECT * FROM tbl_corp_company 
		    WHERE status = 1 AND recmd = 1 AND industry = :industry ORDER BY rand() LIMIT {$limit};", 
		    array('industry' => $industry)
		);
    }

    /**
     * 查找全部企业
     * 
     * @param string $orderField
     * @param string $orderType
     * @param string $limit
     * @return array
     */
	public function selectAll($orderField='cid', $orderType='', $limit='10')
    {
		return $this->_dao->fetchAll("SELECT * FROM {$this->_name} 
		    WHERE status = 1 ORDER BY {$orderField} {$orderType} LIMIT {$limit};", array()
	    );
    }

    /**
     * 查找最新加入的行业企业
     * 
     * @param string $orderField
     * @param string $orderType
     * @param integer $industry
     * @param string $limit
     * @return array
     */
	public function selectIndustryJoinAll($orderField='cid', $orderType='', $industry, $limit='10')
    {
		return $this->_dao->fetchAll("SELECT * FROM {$this->_name} 
		    WHERE status = 1 AND industry = :industry ORDER BY {$orderField} {$orderType} LIMIT {$limit};", 
		    array('industry' => $industry)
	    );
    }

    /**
     * 查找uid按status的全部企业
     * 
     * @param integer $uid
     * @param integer $status
     * @param string $orderField
     * @param string $orderType
     * @return array
     */
	public function selectUidStatusAll($uid, $status, $orderField='cid', $orderType='')
    {
		return $this->_dao->fetchAll("SELECT * FROM {$this->_name} 
		    WHERE uid = :uid AND status = :status ORDER BY {$orderField} {$orderType};", 
		    array('uid' => $uid, 'status' => $status)
	    );
    }

	/**
     * 更新cid的页面浏览量
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $args['cid'])
		);
    }

	/**
     * 更新cid和uid的企业基础信息
     * 
     * @param array $args
     * @return integer
     */
	public function updateBasic($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET industry = :industry, property = :property, 
    	    province = :province, city = :city, intro = :intro 
    	    WHERE cid = :cid AND uid = :uid AND status = 1;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }

	/**
     * 更新cid的企业状态(管理员功能)
     * 
     * @param array $args
     * @return integer
     */
	public function updateStatus($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET status = :newStatus 
    	    WHERE cid = :cid AND status = :oldStatus;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
