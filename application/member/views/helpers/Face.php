<?php

/**
 * @category   zjuhz.com
 * @package    member
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
	 * @param string $type(smaller/medium/larger/original)
     * @return string or boolean
     */
	public static function face($uid, $type='small')
    {
    	return Commons::getUserFace($uid, $type);
    }
}
