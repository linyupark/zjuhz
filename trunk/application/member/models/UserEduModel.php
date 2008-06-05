<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserEduModel.php
 */


/**
 * 校友中心-tbl_user_edu
 */
class UserEduModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_edu';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'eid';

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
     * 查找eid存在数量
     * 
     * @param string $eid
     * @param integer $uid
     * @return integer
     */
	public function selectEidCount($eid, $uid)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE uid = :uid AND eid = :eid;", array('eid' => $eid, 'uid' => $uid)
    	);
    }

    /**
     * 查找uid对应的教育经历
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
     * 查询eid对应的教育详情
     * 
     * @param string $eid
     * @param integer $uid
     * @return array
     */
	public function selectEidRow($eid, $uid)
    {
		return $this->_dao->fetchRow("SELECT * 
		    FROM {$this->_name} WHERE uid = :uid AND eid = :eid;", 
		    array('eid' => $eid, 'uid' => $uid)
		);
    }

    /**
     * 插入教育记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新教育资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $args['eid'])
		);
    }

    /**
     * 删除教育记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
    	$stmt = $this->_dao->prepare("DELETE FROM {$this->_name} 
    	    WHERE uid = :uid AND eid = :eid;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
