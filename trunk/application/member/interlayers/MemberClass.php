<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberClass.php
 */


/**
 * 校友中心
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
    	parent::__construct();
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	parent::__destruct();
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
     * 转换sess内的字符串为数组
     * see manual with function session_decode()
     * 
     * @param string $sess_string
     * @return boolean|array
     */
    public static function decodeSession($sess_string)
    {
    	// save current session data
        //   and flush $_SESSION array
        $old = $_SESSION;
        $_SESSION = array();

        // try to decode passed string
        $ret = session_decode($sess_string);
        if (!$ret)
        {
        	// if passed string is not session data,
            //   retrieve saved (old) session data
            //   and return false
            $_SESSION = array();
            $_SESSION = $old;

            return false;
        }

        // save decoded session data to sess_array
        //   and flush $_SESSION array
        $sess_array = $_SESSION;
        $_SESSION = array();

        // restore old session data
        $_SESSION = $old;

        // return decoded session data
        return $sess_array;
    }
}
