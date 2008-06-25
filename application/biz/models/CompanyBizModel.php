<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CompanyBizModel.php
 */


/**
 * 校友企业-tbl_corp_company_biz
 * 表级操作类,含单表读/写/改等方法
 */
class CompanyBizModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp_company_biz';

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
     * 更新cid和uid的企业商务资料
     * 
     * @param array $args
     * @return integer
     */
	public function updateBiz($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET product = :product, job = :job, 
            cooperate = :cooperate WHERE cid = :cid AND uid = :uid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
