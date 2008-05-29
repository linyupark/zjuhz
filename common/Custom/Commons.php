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

	#简单数据解密 $int 数字密钥
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

	#获取客户端IP
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

	# 关键字加亮 string $keys(空格为分隔)
	static function highlight($keys, $str, $sp = ' ', $class = 'search_highlight')
	{
		$keyArr = explode($sp, $keys);
		foreach ($keyArr as $key => $val)
		{
			$str = str_replace($val, '<span class="search_highlight">'.$val.'</span>', $str);
		}

		return $str;
	}

    /**
     * 修改session内的值与参数$array对应
     * 
     * @param string $nameSpace
     * @param string $nameId
     * @param array $array 
     * @return string or true
     */
	static function modiSess($nameSpace, $nameId, $array)
	{
		foreach ($array as $key => $value)
		{
			$_SESSION[$nameSpace][$nameId][$key] = $array[$key];
		}
	}

    /**
     * 获取随机字符串
     * 
     * @param mixed $base
     * @param integer $len
     * @return string
     */
	static function getRandomStr($base,$len)
    {
    	return substr(md5(uniqid(rand() + $base, true)), 0, $len);
	}
	
	/**
	 * 下载指定文件
	 * 
	 * @param string $file 完整下载路径
	 * @param string $name 另存为的名称
	 * @param boolean $delete 是否在下载完后删除
	 */
	function download($file, $name, $delete = false)
    {
      header("Content-type:application/x-octet-stream");
      header("Content-Disposition:attachment;filename=$name");
      readfile($file);
      if ($delete == ture)
        unlink($file);
    }

	/**
	 * 获取用户文件夹路径(若缺少则自动创建)
	 * for example:if uid = 1 then return "/static/users/0/1/"
	 * 
	 * @param integer $uid
	 * @param string $sub
     * @return string or boolean
	 */
    static function getUserFolder($uid, $sub='')
    {
    	// 用户文件夹下各子文件夹
    	$subArray = array('' => '', 'albums' => 'albums/');

    	if (0 < $uid && array_key_exists($sub, $subArray))
    	{
    		$folder      = "/static/users/".intval($uid/1000)."/{$uid}/{$subArray[$sub]}";
            $folderArray = explode('/', $folder);

            if(!file_exists("..{$folder}"))
	        {
	        	foreach ($folderArray as $value)
                {
                	$path .= "{$value}/";
            	    $dir  = "..$path";

            	    (0 <= $value && !file_exists($dir) ? (mkdir($dir, 0777) ? 
            	        chmod($dir, 0777) : '') : '' );
		        }
	        }

	        return $folder;
    	}

    	return false;
    }

	/**
	 * 获取用户头像
	 * 
	 * @param integer $uid
	 * @param string $type(small-30*30/medium-54*54/large-200*200/original/square)
     * @return string or boolean
	 */
    static function getUserFace($uid, $type='medium')
    {
    	// if uid = 1 then <img src="/static/users/0/1/medium.jpg" onerror=this.src="/static/images/default-face.jpg";>
    	return (0 < $uid ? '<img src="'.self::getUserFolder($uid).$type.'.jpg" 
    	    onerror=this.src="/static/images/default-face.jpg";>' : false
    	);
    }
}
