<?php

/**
	 * 实用命令,静态调用
	 *
	 */
class Commons
{
	#用JS方式进行到 $url 的跳转, 可停顿 $sec 秒后执行
	static function js_jump($url, $sec = 0)
	{
		$sec = $sec * 1000;
		if ($sec == 0)
		return "<script type='text/javascript'>location.href='{$url}';</script>";
		else
		return "<script type='text/javascript'>var t = setInterval(\"location.href='{$url}'\",{$sec});</script>";
	}

	#HTML特殊字符转换,外加空格回车转换
	static function html2str($text)
	{
		return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($text)));
	}

	#简单数据加密 $int 数字密钥
	static function encrypy($str, $int = 1)
	{
		$x_str = '';
		$len_str = strlen($str);
		for($i = 0; $i < $len_str; $i++)
		{
			$x_key = ($i + $len_str) + $int;
			//未知的钥匙值不能大于255
			$x_key = ($x_key + 255) % 255;
			$x_str .= chr(ord(substr($str, $i, 1)) ^ $x_key);
		}
		return base64_encode($x_str);
	}

	static function decrypt($x_str, $int = 1)
	{
		$str = '';
		$x_str = base64_decode($x_str);
		$len_str = strlen($x_str);
		for($i = 0; $i < $len_str; $i++)
		{
			$x_key = ($i + $len_str) + $int;
			$x_key = ($x_key + 255) % 255;
			$str .= chr(ord(substr($x_str, $i, 1)) ^ $x_key);
		}
		return $str;
	}
	
	static function date($timestamp = null)
	{
		if($timestamp == null)
		$timestamp = time();
		return date('y-m-d H:i',$timestamp);
	}
	
    static function getIp()
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
   		else
   		{
   			$ip = "unknown";
   		}

   		return($ip);
    }
}
