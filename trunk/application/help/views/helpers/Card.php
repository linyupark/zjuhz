<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Card.php
 */

class Zend_View_Helper_Card
{
	/**
     * 显示用户姓名
     * 
	 * @param integer $uid
	 * @param string $name
	 * @param string $anonym
     * @return string
     */
	public static function card($uid, $name, $anonym='N')
    {
    	return ('N' == $anonym ? "<a href='javascript:ucard({$uid})'>{$name}</a>" : '匿名');
    }
}
