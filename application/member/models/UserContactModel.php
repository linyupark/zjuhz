<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserContactModel.php
 */


/**
 * 校友中心-tbl_user_contact
 * 表级操作类,含单表读/写/改等方法
 */
class UserContactModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user_contact';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'uid';

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

    public function fetchCol($uid, $col)
    {
        $row = $this->_dao->fetchRow('SELECT `'.$col.'` FROM `tbl_user_contact` WHERE `uid` = '.$uid);
        if($col != '*') return $row[$col];
        else return $row;
    }
    
    /**
     * 更新会员联络资料
     * 
     * @param array $args
     * @param integer $uid
     * @return integer
     */
	public function update($args, $uid)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $uid)
		);
    }
}
