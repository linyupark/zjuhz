<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Companyface.php
 */

class Zend_View_Helper_Companyface
{
	/**
     * 显示企业头像
     * 
	 * @param string $cid
	 * @param string $type
     * @return string or boolean
     */
	public static function Companyface($cid, $type='medium')
    {
    	return Commons::getCompanyFace($cid, $type);
    }
}
