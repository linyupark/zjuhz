<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberClass.php
 */


/**
 * 会员中心
 * member子系统-通用类库
 */
class MemberClass extends MemberInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	//parent::__construct();
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	//parent::__destruct();
    }

    /**
     * 类实例
     * 
     * @return object
     */
	public static function init()
    {
    	return parent::_getInstance(__CLASS__);
    }

    /**
     * session内的验证码校验
     * 
     * @param integer $input
     * @param string $sess
     * @return string or true
     */
	public static function checkVerifyCode($input,$sess)
	{
		if ($sess !== md5($input)) { exit('请正确输入验证码，应是四位纯数字。');	}

		return true;
	}
}
