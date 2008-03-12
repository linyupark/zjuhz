<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : UserModel.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-会员主表
 */
class UserModel extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */	
    protected $_name = 'tbl_user';

    /**
     * 数据表主键
     * @var string
     */    
    protected $_primary = 'uid';

    /**
     * 数据库操作类
     * @var object
     */    
    protected $_db = null;

    /**
     * 初始化
     * 
     * @return null
     */
    public function __construct()
    {
    	parent::__construct();

    	//载入数据库操作类
        $this->_db = Zend_Registry::get('db');

        return null;
    }

    /**
     * 会员注册
     * 判断登录帐号是否已存在
     * 判断邀请码是否可用
     * 
     * 
     * @return string
     */
	public function register($array)
    {
    	print_r($array);
    	
    	$this->_isExist();
    }

    /**
     * 是否已存在
     * 
     * @return string
     */
	private function _isExist()
    {
    }
}
