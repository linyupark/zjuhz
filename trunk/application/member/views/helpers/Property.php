<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Property.php
 */

class Zend_View_Helper_Property
{
	/**
     * 载入公司性质(数组)
     * 
     * @return array or string
     */
	public static function property($key=0)
    {
    	$name = Zend_Registry::get('iniCommon')->property->name;

    	return (0 < $key ? $name->$key : $name->toArray());
    }
}
