<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Face.php
 */

class Zend_View_Helper_Face
{
	/**
     * 显示用户头像
     * 
	 * @param integer $uid
	 * @param string $type
     * @return string or boolean
     */
	public static function face($uid, $type='medium')
    {
    	return Commons::getUserFace($uid, $type);
    }
}
