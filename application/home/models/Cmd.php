<?php

class Cmd
{
    static function icon($name)
	{
		return '<img src="/static/images/group/icons/'.$name.'" />';
	}
    
    /**
	 * 直接返回当前访问用户的id
	 *
	 * @return int
	 */
	static function myid()
	{
		return Zend_Registry::get('sessCommon')->login['uid'];
	}
}

?>