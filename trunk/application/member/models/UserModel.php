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
     * @param array $regInfo
     * @return string
     */
	public function register($regInfo)
    {
    	// Start a transaction explicitly.
		$this->_db->beginTransaction();

		try
		{
			$this->_db->insert($this->_name,$regInfo);
			$uid = $this->_db->lastInsertId();
			if ($uid > 0)
			{
				$this->_db->commit();
				echo 'ok';
			}
		}
		catch (Exception $e)
	 	{
	 		$this->_db->rollBack();
    		echo $e->getMessage();
		}
    }

    /**
     * 判断用户登录帐号是否已存在
     * 
     * @param string $userName
     * @return boolean
     */
	public function isUserNameExist($userName)
    {
    	$select = $this->_db->select()
    		    ->from($this->_name,'uid')
    		    ->where('userName = ?',$userName);

    	return (($this->_db->fetchRow($select)) ? true : false);	
    }

    /**
     * 判断用户注册邀请码是否可用
     * 
     * @param string $ikey
     * @param numeric $iuid
     * @return boolean | array
     */
	public function getInviteDetail($ikey,$iuid)
    {
    	$select = $this->_db->select()
    		    ->from('tbl_user_invite_detail')
    		    ->where('ikey = ?',$ikey)
    		    ->where('iuid = ?',$iuid);

    	$rows = $this->_db->fetchRow($select);
    	return (($rows == null) ? false : $rows);
    }

    /**
     * 析构方法
     * 
     * @return null
     */
	public function __destruct()
    {
    	$this->_db->closeConnection();
    	return null;
    }
}
