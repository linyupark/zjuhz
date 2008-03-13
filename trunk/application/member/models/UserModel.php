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
     * 配置文档对象
     *
     * @var object
     */
	private $_ini = null;

	/**
     * 公共SESSION对象
     *
     * @var array
     */
	private $_sessCommon = null;

    /**
     * 构造方法
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
     * 析构方法
     * 
     * @return null
     */
	public function __destruct()
    {
    	$this->_db->closeConnection();
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

		if (!self::chkVerifyCode($this->_sessCommon->verify,
		    $this->_request->getPost('vcode')))
		{
			echo $this->_ini->hint->verifyCode->checkError;
		}
		elseif (!self::chkUserName($userName))
		{
			echo $this->_ini->hint->userName->formatError;
		}
		elseif (!self::chkPasswd($passWord))
		{
			echo $this->_ini->hint->passWord->formatError;
		}
		elseif (!Valid::equal($passWord,$rePasswd))
		{
			echo $this->_ini->hint->rePasswd->notEqual;
		}
		elseif (!self::chkRealName($realName))
		{
			echo $this->_ini->hint->realName->formatError;
		}
		elseif (!self::chkEmail($eMail))
		{
			echo $this->_ini->hint->eMail->formatError;
		}    	
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
     * 检查登录帐号3-16位字母数字下划线
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkUserName($input)
	{
		return ((!Valid::isAlphaNumUline($input) || !Valid::alphaNumLenRange($input,3,16)) ? false : true);
	}

	/**
     * 检查登录密码6-16位不含空格
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkPasswd($input)
	{
		return ((!Valid::alphaNumLenRange($input,6,16) || !Valid::isFullIncluding($input,' ')) ? false : true);
	}

	/**
     * 检查真实姓名 2-16位，且不能含有数字和符号 中日英韩且不能混合
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkRealName($input)
	{
		return ((!Valid::utf8NotMixed($input) || !Valid::utf8LenRange($input,2,16)) ? false : true);
	}

	/**
     * 检查附加码 4位
     * 
     * @param string $input1 SESSION内已加解的
     * @param string $input2 用户输入的原始数据
     * @return boolean
     */
	public static function chkVerifyCode($input1,$input2)
	{
		return ((!Valid::equal($input1,md5($input2)) || !Valid::alphaNumLenRange($input2,4,4)) ? false : true);
	}
}
