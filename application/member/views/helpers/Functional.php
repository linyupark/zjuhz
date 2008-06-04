<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Functional.php
 */

class Zend_View_Helper_Functional
{
	/**
     * 载入工作职能(数组)
     * 
     * @return array or string
     */
	public static function functional($key=0)
    {
    	$name = Zend_Registry::get('iniCommon')->functional->name;

    	return (0 < $key ? $name->$key : $name->toArray());
    }
}
