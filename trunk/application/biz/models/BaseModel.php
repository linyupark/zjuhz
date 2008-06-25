<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:BaseModel.php
 */


/**
 * 校友企业-tbl_base
 * 表级操作类,含单表读/写/改等方法
 */
class BaseModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_base';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = '';

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
     * 查找基础数据资料
     * 
     * @return array
     */
	public function selectRow()
    {
    	return $this->_dao->fetchRow("SELECT * FROM {$this->_name};", array());
    }

	/**
     * 更新基础数据资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, '');
    }
}
