<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Filter.php $
 */


/**
 * 数据过滤
 */
class Filter
{
	/**
     * 过滤获取到的外部请求参数 内置：trim|strip_tags|addslashes
     * 用于安全地过滤一切$_GET或$_POST到的数据     * 
     * 
     * @param string|array $input
     * @return string
     */
	public static function request($input)
	{
		if (is_array($input))
		{
			foreach ($input as $key => &$value)
			{
				$value = strip_tags(trim($value),null);
				$input[$key] = (!get_magic_quotes_gpc() ? addslashes($value) :$value);
			}
		}
		else
		{
			$input = strip_tags(trim($input),null);
			$input = (!get_magic_quotes_gpc() ? addslashes($input) : $input);
		}
		//echo $input;exit;
		//print_r($input);exit;
		return $input;
	}
}
