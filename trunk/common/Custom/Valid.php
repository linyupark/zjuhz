<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Valid.php $
 */


/**
 * 数据验证
 */
class Valid
{
	/**
     * 校验登录账号
     * 字母数字下划线3-16位
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkUsername($input)
	{
		return ((self::isAlphaNumUline($input) && self::alphaNumLenRange($input,3,16)) ? true : false);
	}

	/**
     * 校验登录密码
     * 3-16位不含空格
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkPasswd($input)
	{
		return ((self::isFullIncluding($input,' ') && self::alphaNumLenRange($input,6,16)) ? true : false);
	}

	/**
     * 字母Alpha 数字Num 下划线Uline
     * Alpha-numeric with underline
     * 
     * @param string $input
     * @return boolean
     */
	public static function isAlphaNumUline($input)
	{
		return ((preg_match('/^([a-z0-9_])+$/i',$input)) ? true : false);
	}

	/**
     * 长度范围检测 字母数字常规版
     * 
     * @param string $input
     * @param numeric $min
     * @param numeric $max
     * @return boolean
     */
	public static function alphaNumLenRange($input,$min,$max)
	{
		$strlen = strlen($input);
		return (($strlen >= $min && $strlen <= $max) ? true : false);
	}

	/**
     * 是否全部包括 一个不包括即返回false
     * 包括返回false 不包括返回true
     * 
     * @param string $input
     * @param array|string $findme
     * @return boolean
     */
	public static function isFullIncluding($input,$findme)
	{
		if (is_array($findme))
		{
			foreach ($findme as &$value)
			{
				if (strpos($input, $value) !== false)
				{
					return false;
				}
			}
		}
		else
		{
			return ((strpos($input, $findme) !== false) ? false : true);
		}

		return true;
	}

	/**
     * 相等(区分大小写)
     * 相等返回true 不相等返回false
     * 
     * @param string $input1
     * @param string $input2
     * @return boolean
     */
	public static function equal($input1,$input2)
	{
		return ((strcmp($input1, $input2) == 0) ? true : false);
	}

	/**
     * 是否是邮箱格式
     * 
     * @param string $input
     * @return boolean
     */
	public static function isEmail($input)
	{
		return ((preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',$input)) ? true : false);
	}
}
