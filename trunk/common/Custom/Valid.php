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
     * 字母al 数字Num 下划线Uline
     * Alpha-numeric with underline
     * 
     * @param string $input
     * @return boolean
     */
	public static function alNumUline($input)
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
	public static function alNumLen($input,$min,$max)
	{
		$len = strlen($input);
		return (($len >= $min && $len <= $max) ? true : false);
	}

	/**
     * 不包括 
     * 包括返回false 不包括返回true
     * 
     * @param string $input
     * @param array|string $findme
     * @return boolean
     */
	public static function notIncluding($input,$findme)
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
}
