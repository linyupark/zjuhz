<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Calendar.php
 */

class Zend_View_Helper_Calendar
{
	public static function calendar()
    {
    	return '<script type="text/javascript" src="/static/scripts/calendar.js"></script>
    	    <script type="text/javascript" src="/static/scripts/calendar-setup.js"></script>
    	    <script type="text/javascript" src="/static/scripts/calendar-zh.js"></script>
    	';
    }
}
