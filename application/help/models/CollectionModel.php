<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CollectionModel.php
 */


/**
 * 你问我答 - tbl_ask_collection
 * 表级操作类,含单表读/写/改等方法
 */
class CollectionModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */	
    protected $_name = 'tbl_ask_collection';

    /**
     * 数据表主键
     * @var string
     */    
    protected $_primary = 'cid';

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
     * 收藏问题
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		// 插入数据行并返回行数
		return $this->_dao->insert($this->_name, $args);
    }
}
