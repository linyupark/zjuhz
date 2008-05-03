<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Pcas.php
 */

class Zend_View_Helper_Pcas
{
	/**
     * 载入省/市/县三级联动选择器
     * 
     * @return string
     */
	public static function pcas()
    {
    	return '<script type="text/javascript" src="/static/scripts/pcas.js"></script>';
    }
}
