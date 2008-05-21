<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:InviteLogModel.php
 */


/**
 * 校友中心-tbl_user_invite_log
 * 表级操作类,含单表读/写/改等方法
 */
class InviteLogModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_invite_log';

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
     * 查询cid对应的邀请日志详情
     * 
     * @param string $cid
     * @return array
     */
	public function selectCidRow($cid)
    {
		return $this->_dao->fetchRow("SELECT * FROM {$this->_name} 
		    WHERE cid = :cid;", array('cid' => $cid) 
        );
    }

    /**
     * 插入邀请日志记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新邀请日志资料
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
