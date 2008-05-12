<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AddressGroupModel.php
 */


/**
 * 会员中心-tbl_user_address_group
 */
class AddressGroupModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_address_group';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'gid';

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
     * @param string $gid
     * @return integer
     */
	public function selectPrimaryExist($gid)
    {
    	return $this->_dao->fetchOne("SELECT COUNT(*) FROM {$this->_name} 
    	    WHERE {$this->_primary} = :gid;", array('gid' => $gid)
    	);
    }

    /**
     * 查询组列表
     * 
     * @param integer $uid
     * @return array
     */
	public function selectList($uid)
    {
    	return $this->_dao->fetchAll("SELECT * FROM {$this->_name} 
    	    WHERE uid = :uid;", array('uid' => $uid)
    	);
    }

    /**
     * 写入组数据
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新组数据
     * 
     * @param array $args
     * @param string $gid
     * @return integer
     */
	public function update($args, $gid)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $gid)
		);
    }

    /**
     * 删除组记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
    	$stmt = $this->_dao->prepare("DELETE FROM {$this->_name} 
    	    WHERE gid = :gid AND uid = :uid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
