<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Edulevel.php
 */

class Zend_View_Helper_Edulevel
{
	/**
     * 载入学历名称(数组)
     * 
     * @return array or string
     */
	public static function edulevel($key=0)
    {
    	$name = Zend_Registry::get('iniCommon')->edulevel->name;

    	return (0 < $key ? $name->$key : $name->toArray());
    }
}
