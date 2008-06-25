<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpModel.php
 */


/**
 * 校友企业-tbl_corp
 * 表级操作类,含单表读/写/改等方法
 */
class CorpModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp';

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

    /**
     * 查找uid的模块资料
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidRow($uid)
    {
    	return $this->_dao->fetchRow("SELECT * FROM {$this->_name} 
    	    WHERE uid = :uid;", array('uid' => $uid)
    	);
    }

    /**
     * 插入uid的模块记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

	/**
     * 更新uid的模块资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $args['uid'])
		);
    }
}
