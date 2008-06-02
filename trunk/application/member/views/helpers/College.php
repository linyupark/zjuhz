<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:College.php
 */

class Zend_View_Helper_College
{
	/**
     * 载入院系名称(数组)
     * 
     * @return array or string
     */
	public static function college($key=0)
    {
    	$name = Zend_Registry::get('iniCommon')->college->name;

    	return (0 < $key ? $name->$key : $name->toArray());
    }
}
