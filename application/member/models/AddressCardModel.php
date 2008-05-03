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
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_address_card';

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
     * 主键是否存在
     * 
     * @param string $cid
     * @return integer
     */
	public function selectPrimaryExist($cid)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE {$this->_primary} = :cid;", array('cid' => $cid)
    	);
    }

    /**
     * 查询名片列表
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectList($gid, $uid)
    {
		return $this->_dao->fetchAll("SELECT * 
		    FROM {$this->_name} WHERE gid = :gid AND uid = :uid;", 
		    array('gid' => $gid, 'uid' => $uid)
		);
    }

    /**
     * 查询记录数
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectCount($gid, $uid)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} WHERE gid = :gid AND uid = :uid;", 
		    array('gid' => $gid, 'uid' => $uid)
		);
    }

    /**
     * 查询详细记录
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
     * 列表自定义查找
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
     * 列表自定义统计
     * 
     * @param string $where
     * @return integer
     */
	public function selectFindCount($where)
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
