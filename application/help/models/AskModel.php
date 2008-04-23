<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskModel.php
 */


/**
 * 你问我答 - tbl_ask
 * 表级操作类,含单表读/写/改等方法
 */
class AskModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */	
    protected $_name = 'tbl_ask';

    /**
     * 数据表主键
     * @var string
     */    
    protected $_primary = 'uid';

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
    	//载入数据库操作类
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
     * 激活tbl_ask表
     * 
     * @param array $args
     * @return integer
     */
	public function activate($args)
    {
		// 插入数据行并返回行数
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 登录子系统
     * 
     * @param integer $uid
     * @return array
     */
	public function entry($uid)
    {
    	return $this->_dao->fetchRow("SELECT * FROM {$this->_name} WHERE uid = :uid;", 
    	    array('uid' => $uid));
    }

    /**
     * 更新表数据
     * 
     * @param array $args
     * @param integer $uid
     * @return integer
     */
	public function update($args, $uid)
    {
    	// where语句
		$where = $this->_dao->quoteInto("{$this->_primary} = ?", $uid);

		// 更新表数据,返回更新的行数
		return $this->_dao->update($this->_name, $args, $where);
    }
}
