<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpCompanyLogic.php
 */


/**
 * 校友企业-tbl_corp_company
 * 控制器附属层:模型层操作入口
 */
class CorpCompanyLogic extends BizInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	parent::_initLogic();

    	$this->_load('CorpCompanyModel');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	parent::__destruct();
    }

    /**
     * 类实例
     * 
     * @return object
     */
	public static function init()
    {
    	return parent::_getInstance(__CLASS__);
    }

    /**
     * 初始化企业资料
     * 
     * @param array $args
     * @return boolean
     */
	public function insert($args)
    {
		return $this->_CorpCompanyModel->callInsert($args);
    }

    /**
     * 查找cid的详细资料
     * 
     * @param string $cid
     * @return array
     */
	public function selectCidRow($cid)
	{
		return $this->_CorpCompanyModel->selectCidRow($cid);
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
		return $this->_CorpCompanyModel->selectCidUidRow($cid, $uid);
	}

    /**
     * 查找不分类的随机推荐企业
     * 
     * @param string $limit
     * @return array
     */
	public function selectRandList($limit)
	{
		return $this->_CorpCompanyModel->selectRandList($limit);
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
		return $this->_CorpCompanyModel->selectIndustryRandList($industry, $limit);
	}

    /**
     * 查找最新加入的全部企业
     * 
     * @param string $limit
     * @return array
     */
	public function selectJoinAll($limit)
	{
		return $this->_CorpCompanyModel->selectAll('regTime', 'DESC', $limit);
	}

    /**
     * 查找最新加入的行业企业
     * 
     * @param integer $industry
     * @param string $limit
     * @return array
     */
	public function selectIndustryJoinAll($industry, $limit)
	{
		return $this->_CorpCompanyModel->selectIndustryJoinAll('regTime', 'DESC', $industry, $limit);
	}

    /**
     * 查找已发布上网的企业
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidValidAll($uid)
	{
		return $this->_CorpCompanyModel->selectUidStatusAll($uid, 1, 'regTime', 'DESC');
	}

    /**
     * 查找审核中的企业
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidAuditingAll($uid)
	{
		return $this->_CorpCompanyModel->selectUidStatusAll($uid, 0, 'regTime');
	}

    /**
     * 查找待审核的企业
     * 
     * @return array
     */
	public function selectAuditingAll()
	{
		return $this->_CorpCompanyModel->selectStatusAll(0);
	}

	/**
     * 更新cid的企业资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
	{
		return $this->_CorpCompanyModel->update($args);
	}

	/**
     * 更新cid企业的页面浏览量
     * 
     * @param string $cid
     * @return integer
     */
	public function updatePageview($cid)
    {
		return $this->_CorpCompanyModel->update(array(
		    'cid' => $cid, 'pageview' => new Zend_Db_Expr('pageview + 1'))
		);
    }

	/**
     * 更新cid企业的页面访问量
     * 
     * @param string $cid
     * @return integer
     */
	public function updateFace($cid)
    {
		return $this->_CorpCompanyModel->update(array(
			'cid' => $cid, 'face' => 'Y')
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
		return $this->_CorpCompanyModel->updateBasic($args);
    }

	/**
     * 更新cid的企业状态-审核通过(管理员功能)
     * 
     * @param string $cid
     * @return integer
     */
	public function updateStatusValid($cid)
    {
		return $this->_CorpCompanyModel->updateStatus(array('cid' => $cid, 
		    'oldStatus' => 0, 'newStatus' => 1)
	    );
    }
}
