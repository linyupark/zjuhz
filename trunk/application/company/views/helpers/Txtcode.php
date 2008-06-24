<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Txtcode.php
 */

class Zend_View_Helper_Txtcode
{
	/**
     * 文本字符转换
     * 
     * @param string $str
     * @return string
     */
	public static function txtcode($str)
    {
    	return Commons::txtcode($str);
    }
}
