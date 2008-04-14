<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ExtModel.php
 */


/**
 * 会员中心-tbl_user_ext
 * 表级操作类,含单表读/写/改等方法
 */
class ExtModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_user_ext';

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
    	// 载入数据库操作类
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
     * 更新表数据
     * 
     * @param array $input
     * @param integer $uid
     * @return integer
     */
	public function update($input, $uid)
    {
    	// where语句
		$where = $this->_dao->quoteInto("{$this->_primary} = ?", $uid);

		// 更新表数据,返回更新的行数
		return $this->_dao->update($this->_name, $input, $where);
    }
}
