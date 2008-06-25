<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Industry.php
 */

class Zend_View_Helper_Industry
{
	/**
     * 载入行业名称(数组)
     * 
     * @return array or string
     */
	public static function industry($key=0)
    {
    	$name = Zend_Registry::get('iniCommon')->industry->name;

    	return (0 < $key ? $name->$key : $name->toArray());
    }
}
