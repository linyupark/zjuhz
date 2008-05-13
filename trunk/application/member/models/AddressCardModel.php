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
     * 查找字段存在数量
     * 
     * @param string $field
     * @param string $cid
     * @return integer
     */
	public function selectFieldCount($field, $value)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE {$field} = :field;", array('field' => $value)
    	);
    }

    /**
     * 查询cid对应的名片详情
     * 
     * @param string $cid
     * @param integer $uid
     * @return array
     */
	public function selectCidRow($cid, $uid)
    {
		return $this->_dao->fetchRow("SELECT * 
		    FROM {$this->_name} WHERE cid = :cid AND uid = :uid;", 
		    array('cid' => $cid, 'uid' => $uid)
		);
    }

    /**
     * 查询gid对应的名片组
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectGidAll($gid, $uid)
    {
		return $this->_dao->fetchAll("SELECT * 
		    FROM {$this->_name} WHERE gid = :gid AND uid = :uid;", 
		    array('gid' => $gid, 'uid' => $uid)
		);
    }

    /**
     * 查询gid对应的记录数
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectGidCount($gid, $uid)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} WHERE gid = :gid AND uid = :uid;", 
		    array('gid' => $gid, 'uid' => $uid)
		);
    }

    /**
     * 查找自定义条件的名片列表
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
     * 统计自定义条件的记录总数
     * 
     * @param string $where
     * @return integer
     */
	public function selectFindCount($where)
    {
		return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) 
		    FROM {$this->_name} AS c, tbl_user_address_group AS g {$where};"
		);
    }

    /**
     * 插入名片记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新名片资料
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
