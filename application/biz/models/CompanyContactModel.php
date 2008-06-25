<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CompanyContactModel.php
 */


/**
 * 校友企业-tbl_corp_company_contact
 * 表级操作类,含单表读/写/改等方法
 */
class CompanyContactModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp_company_contact';

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
     * 更新cid和uid的企业联系资料
     * 
     * @param array $args
     * @return integer
     */
	public function updateContact($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET phone = :phone, fax = :fax, 
    	    eMail = :eMail, url = :url, address = :address, postcode = :postcode, other = :other
    	    WHERE cid = :cid AND uid = :uid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
