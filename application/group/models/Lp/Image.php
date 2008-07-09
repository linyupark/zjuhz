<?php

class Lp_Image
{
	private $_im; //图象资源
	public $name; //名称
	public $ext; //后缀
	public $width;
	public $height;
	public $size;

	function __construct($file)
	{
		$this->init($file);
	}
	
	#此函数可重新初始化图象资源
	function init($file)
	{
		//有效文件
		if (!file_exists($file))
		throw new Exception("IMAGE FILE '{$file}' IS NOT EXISTS");
		//是否支持GD
		if (!extension_loaded("gd"))
		throw new Exception('THE FUNCTION USED NEED GD EXTENSION!');
		//允许的图片后缀
		$ext_allowed = array("jpg", "jpeg", "png", "gif");
		$filename = pathinfo($file, PATHINFO_BASENAME);
		$filename = explode(".", $filename);
		$this->name = $filename[0];
		$ext = strtolower($filename[1]);
		if (!in_array($ext, $ext_allowed))
		throw new Exception("THIS FILE EXTENSION '{$ext}' IS NOT ALLOWED");
		$this->ext = $ext;
		//建立图片处理对象
		$create_im = array("jpg" => "imagecreatefromjpeg", "jpeg" => "imagecreatefromjpeg", "png" => "imagecreatefrompng", "gif" => "imagecreatefromgif"
		);
		$this->_im = $create_im[$ext]($file);
		//图片信息
		$this->width = imagesx($this->_im);
		$this->height = imagesy($this->_im);
		$this->size = filesize($file);
	}

	# 获取文件大小 , kb
	function get_kb($file = null)
	{
		if ($file == null && $this->_im == null)
		return ;
		else
		{
			if ($this->_im == null)
			$this->init($file);
			return round($this->size / 1024, 1);
		}
	}

	#获取图象最大的边长
	function get_max_len($file = null)
	{
		if ($file == null && $this->_im == null)
		return ;
		else
		{
			if ($this->_im == null)
			$this->init($file);
			if ($this->width > $this->height)
			return $this->width;
			else
			return $this->height;
		}
	}

	#获取图象最小的边长
	function get_min_len($file = null)
	{
		if ($file == null && $this->_im == null)
		return ;
		else
		{
			if ($this->_im == null)
			$this->init($file);
			if ($this->width < $this->height)
			return $this->width;
			else
			return $this->height;
		}
	}

	#以最小边切成正方形
	function square($dest_im_name, $dest_im_ext = null)
	{
		if ($this->_im == null)
		throw new Exception('IMAGE HANDLE IS NOT CREATE YET!');
		else
		{
			$length = $this->get_min_len();
			$dest_im = $this->create($length, $length);
			imagecopy($dest_im, $this->_im, 0, 0, 0, 0, $length, $length);
			$this->_im = $dest_im;
			//$this->destroy($dest_im);
			$this->output($dest_im_name, $dest_im_ext, 85);
			$this->destroy();
		}
	}

	#绝对宽高缩略
	function abs_resize($w, $h, $dest_im_name, $dest_im_ext = null)
	{
		if ($this->_im == null)
		throw new Exception("IMAGE HANDLE IS NOT CREATE YET!");
		$dest_im = $this->create($w, $h);
		if (function_exists("imagecopyresampled"))
		{
			imagecopyresampled($dest_im, $this->_im, 0, 0, 0, 0, $w, $h, $this->width, $this->height);
		}
		else
		{
			imagecopyresized($dest_im, $this->_im, 0, 0, 0, 0, $w, $h, $this->width, $this->height);
		}
		$this->_im = $dest_im;
		//$this->destroy($dest_im);
		$this->output($dest_im_name, $dest_im_ext, 85);
		$this->destroy();
	}

	#百分比缩略(0.9~0.1)
	function per_resize($percent, $dest_im_name, $dest_im_ext = null)
	{
		$w = $this->width * $percent;
		$h = $this->height * $percent;
		$this->abs_resize($w, $h, $dest_im_name, $dest_im_ext);
	}

	#写水印
	function wmark($png_logo, $pos, $pct = 50)
	{
		if (!file_exists($png_logo))
		throw new Exception("PNG LOGO FILE IS NOT EXISTS : ".$png_logo);
		$dest_im = imagecreatefrompng($png_logo);
		$dest_w = imagesx($dest_im);
		$dest_h = imagesy($dest_im);
		$dest_x = 0;
		$dest_y = 0;
		switch ($pos)
		{
			case "lt":
				break;
			case "ct":
				$dest_x = ($this->width - $dest_w) / 2;
				break;
			case "rt":
				$dest_x = $this->width - $dest_w;
				break;
			case "lc":
				$dest_y = ($this->height - $dest_h) / 2;
				break;
			case "cc":
				$dest_x = ($this->width - $dest_w) / 2;
				$dest_y = ($this->height - $dest_h) / 2;
				break;
			case "rc":
				$dest_x = $this->width - $dest_w;
				$dest_y = ($this->height - $dest_h) / 2;
				break;
			case "lb":
				$dest_y = $this->height - $dest_h;
				break;
			case "cb":
				$dest_x = ($this->width - $dest_w) / 2;
				$dest_y = $this->height - $dest_h;
				break;
			case "rb":
				$dest_x = $this->width - $dest_w;
				$dest_y = $this->height - $dest_h;
				break;
		}
		imagecopymerge($this->_im, $dest_im, $dest_x, $dest_y, 0, 0, $dest_w, $dest_h, $pct);
		//$this->destroy($dest_im);
		$this->output($this->name, $this->ext, 85);
		$this->destroy();
	}

	# 验证码 , 可静态调用
	static function verify($session_namespace = 'common', $length = 4, $w = 48, $h = 22, $name = "verify")
	{
		if (!extension_loaded("gd"))
		throw new Exception("THE FUNCTION USED NEED GD EXTENSION!");
		//建立画布;
		if (function_exists("imagecreatetruecolor"))
		$dest_im = imagecreatetruecolor($w, $h);
		else
		$dest_im = imagecreate($w, $h);
		//随机数生成
		$rand = "";
		for ($i = 0; $i < $length; $i++)
		{
			$rand .= mt_rand(0, 9);
		}

		if(!$session_namespace)
		$_SESSION[$name] = md5($rand);
		else $_SESSION[$session_namespace][$name] = md5($rand);

		//随机背景色
		$r = array(225, 255, 255, 223);
		$g = array(225, 236, 237, 255);
		$b = array(225, 236, 166, 125);
		$k = mt_rand(0, 3);
		$bg_color = imagecolorallocate($dest_im, $r[$k], $g[$k], $b[$k]);
		$border = imagecolorallocate($dest_im, 100, 100, 100);
		$point = imagecolorallocate($dest_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		$string = imagecolorallocate($dest_im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
		imagefilledrectangle($dest_im, 1, 1, $w - 1, $h - 1, $bg_color);
		imagerectangle($dest_im, 0, 0, $w - 1, $h - 1, $border);
		// 干扰
		for ($i = 0; $i < 10; $i++)
		{
			$font = imagecolorallocate($dest_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($dest_im, mt_rand( - 10, $w), mt_rand( - 10, $h), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $font);
		}
		for ($i = 0; $i < 25; $i++)
		{
			$font = imagecolorallocate($dest_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($dest_im, mt_rand(0, $w), mt_rand(0, $h), $point);
		}
		//写入
		$margin_top = (int)(($h - 16) / 2);
		imagestring($dest_im, 5, 5, $margin_top, $rand, $string);
		//直接输出
		header("Content-type: image/png");
		imagepng($dest_im);
		imagedestroy($dest_im);
	}

	# 建立画布
	function create($w, $h)
	{
		if (function_exists("imagecreatetruecolor"))
		return imagecreatetruecolor($w, $h);
		else
		imagecreate($w, $h);
	}

	#图片输出
	function output($name, $ext = null, $quality = null)
	{
		if ($this->_im == null)
		throw new Exception("NO IMAGE CAN BE OUTPUT!");
		if ($ext != null)
		$ext = strtolower($ext);
		else
		$ext = $this->ext;
		$output_im = array("jpg" => "imagejpeg", "jpeg" => "imagejpeg", "png" => "imagepng", "gif" => "imagegif"
		);
		if (!isset($output_im[$ext]))
		{
			throw new Exception("THIS FILE EXTENSION IS NOT ALLOWED : ".$ext);
		}
		if ($ext == "jpg" || $ext == "jpeg")
		$output_im[$ext]($this->_im, $name.".".$ext, $quality);
		else
		$output_im[$ext]($this->_im, $name.".".$ext);
	}

	# 销毁
	function destroy($_im = null)
	{
		if ($_im == null && $this->_im != null)
		{
			imagedestroy($this->_im);
			$this->_im = null;
		}
		else
		{
			imagedestroy($_im);
		}
	}
}