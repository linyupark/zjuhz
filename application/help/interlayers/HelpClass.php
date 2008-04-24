<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpClass.php
 */


/**
 * 你问我答
 * help子系统-通用类库
 */
class HelpClass extends HelpInterlayer
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
	public static function checkVerifyCode($input, $sess)
	{
		if ($sess !== md5($input)) { exit('请正确输入验证码，应是四位纯数字。');	}

		return true;
	}

    /**
     * 获取当前分类路线图
     * 
     * @param array $sort
     * @param integer $sid
     * @param string $path
     * @return string or false
     */
	public static function getSortPath($sort, $sid, $path='')
	{
		foreach ($sort as $value)
		{
			if ($sid == $value['sid'])
			{
				$path = '<a href="/help/sort/browse/sid/'.$value['sid'].'/" title="'.$value['name'].'">'.$value['name'].'</a>'.'&nbsp;>&nbsp;'.$path;

				if ('0' !== $value['parent']) {	$path = self::getSortPath($sort, $value['parent'], $path); }

				return $path;
			}
		}

		return false;
	}

    /**
     * 获取当前分类所属图
     * 
     * @param array $sort
     * @param integer $sid
     * @return array
     */
	public static function getSortMap($sort, $sid)
	{
		foreach ($sort as $value)
		{
			if ($sid == $value['parent']) {	$map[] = $value; }

			if ($sid == $value['sid']) { $parent = $value['parent']; }
		}

		if (!is_array($map)) { return self::getSortMap($sort, $parent); }

		return $map;
	}

    /**
     * 获取当前分类所属图
     * 
     * @param array $sort
     * @param integer $sid
     * @return array or false
     */
	public static function getSortDetail($sort, $sid)
	{
		foreach ($sort as $value) {	if ($sid == $value['sid']) { return $value; } }

		return false;
	}

    /**
     * 获取当前分类家谱
     * 
     * @param array $sort
     * @param integer $sid
     * @return array or false
     */
	public static function getSortGeneal($sort, $sid, $geneal='')
	{
		foreach ($sort as $value)
		{
			if ($sid == $value['sid'])
			{
				$geneal[] = $value;

				if ('0' !== $value['parent']) {	$geneal = self::getSortGeneal($sort, $value['parent'], $geneal); }

				krsort($geneal);
				return $geneal;
			}
		}

		return false;
	}
}
