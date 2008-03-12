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

	/**
     * 是否为中c日j韩k英e且不能混合
     * 如:不能既有汉字又有英文字母 不能既有字母又有韩文等
     * 
     * @param string $input
     * @return boolean
     */
	public static function utf8NotMixed($input)
	{
		$input;
		return true;
	}

	/**
     * utf8字符长度
     * 
     * @param string $input
     * @return numeric
     */
	public static function utf8StrLen($input)
	{
		$i     = 0;
		$count = 0;
		$len   = strlen($input);

		while ($i < $len)
		{
			$chr = ord($input[$i]);
			$count++;
			$i++;

			if ($i >= $len) {break;}

			if ($chr & 0x80)
			{
				$chr <<= 1;
				while ($chr & 0x80)
				{
					$i++;
					$chr <<= 1;
				}
			}
		}
		return $count;
	}

	/**
     * 长度范围检测 utf编码版
     * 
     * @param string $input
     * @param numeric $min
     * @param numeric $max
     * @return boolean
     */
	public static function utf8LenRange($input,$min,$max)
	{
		$strlen = self::utf8StrLen($input);
		return (($strlen >= $min && $strlen <= $max) ? true : false);
	}
}
