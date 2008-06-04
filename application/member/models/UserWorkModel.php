<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserWorkModel.php
 */


/**
 * 校友中心-tbl_user_work
 */
class UserWorkModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_work';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'wid';

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
     * 查找wid存在数量
     * 
     * @param string $wid
     * @param integer $uid
     * @return integer
     */
	public function selectWidCount($wid, $uid)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE uid = :uid AND wid = :wid;", array('wid' => $wid, 'uid' => $uid)
    	);
    }

    /**
     * 查找uid对应的工作经验
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidAll($uid)
    {
    	return $this->_dao->fetchAll("SELECT * FROM {$this->_name} 
    	    WHERE uid = :uid ORDER BY startDate DESC;", array('uid' => $uid)
    	);
    }

    /**
     * 查询wid对应的工作详情
     * 
     * @param string $wid
     * @param integer $uid
     * @return array
     */
	public function selectWidRow($wid, $uid)
    {
		return $this->_dao->fetchRow("SELECT * 
		    FROM {$this->_name} WHERE wid = :wid AND uid = :uid;", 
		    array('wid' => $wid, 'uid' => $uid)
		);
    }

    /**
     * 插入工作记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新工作资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $args['wid'])
		);
    }

    /**
     * 删除工作记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
    	$stmt = $this->_dao->prepare("DELETE FROM {$this->_name} 
    	    WHERE wid = :wid AND uid = :uid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
