<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : UserModel.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-帐号处理
 * 面向前后端:帐号创建 资料修改 修改修改等
 * 纯数据处理，不做页面层显示
 */
class UserModel extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     */	
    protected $_name = 'tbl_user';

    /**
     * 数据表主键
     */    
    protected $_primary = 'uid';

    /**
     * 初始化
     */
    public function __construct()
    {echo 'TEST';
    	parent::__construct();
        $this->db = Zend_Registry::get('db');	
    }
    
    /**
     * 会员注册
     */
	public function register($array)
    {
    	print_r($array);
    	$this->insert($array)
    }
}
