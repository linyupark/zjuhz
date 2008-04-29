<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AddressCardModel.php
 */


/**
 * 会员中心-tbl_user_address_card
 */
class AddressCardModel
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_user_address_card';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'cid';

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
    	// 载入数据库操作类
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
     * 主键存在与否
     * 
     * @param string $cid
     * @return integer
     */
	public function selectPrimaryExist($cid)
    {
    	return $this->_dao->fetchOne("SELECT COUNT(*) FROM {$this->_name} 
    	    WHERE {$this->_primary} = :cid;", array('cid' => $cid)
    	);
    }

    /**
     * 查询列表
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectList($gid, $uid)
    {
		return $this->_dao->fetchRow("SELECT cname,mobile,eMail,qq,msn,address,postcode,memo 
		    FROM {$this->_name} WHERE gid = :gid AND uid = :uid;", 
		    array('gid' => $gid, 'uid' => $uid)
		);
    }

    /**
     * 查询详细
     * 
     * @param string $cid
     * @param integer $uid
     * @return array
     */
	public function selectDetail($cid, $uid)
    {
		return $this->_dao->fetchRow("SELECT * 
		    FROM {$this->_name} WHERE cid = :cid AND uid = :uid;", 
		    array('cid' => $cid, 'uid' => $uid)
		);
    }

    /**
     * 查询列表-查询
     * 
     * @param string $where
     * @param string $limit
     * @return array
     */
	public function selectFindList($where, $limit)
    {
		return $this->_dao->fetchAll("SELECT * 
		    FROM {$this->_name} AS c, tbl_user_address_group AS g {$where} 
		    ORDER BY c.lastModi DESC LIMIT {$limit};"
		);
    }

    /**
     * 查询列表-统计
     * 
     * @param string $where
     * @return integer
     */
	public function selectCountList($where)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} AS c, tbl_user_address_group AS g {$where};");
    }

    /**
     * 常规写入数据
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 常规更新数据
     * 
     * @param array $args
     * @param string $cid
     * @return integer
     */
	public function update($args, $cid)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $cid)
		);
    }
}
