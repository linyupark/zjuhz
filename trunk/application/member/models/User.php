<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : User.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-会员主表
 */
class User extends Zend_Db_Table_Abstract
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
    private $db = null;

    /**
     * 初始化
     * 
     * @return null
     */
    public function __construct()
    {
    	parent::__construct();
    	
        $this->db = Zend_Registry::get('db');        
        
        return null;
    }
    
    /**
     * 会员注册
     * 
     * @return string
     */
	public function register($array)
    {
    	print_r($array);
    }
}
