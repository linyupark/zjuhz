<?php

class Cmd
{
	/**
	 * 直接返回当前访问用户的id
	 *
	 * @return int
	 */
	static function myid()
	{
		return Zend_Registry::get('sessCommon')->login['uid'];
	}
	
	static function getCateName($cid)
	{
		$db = Zend_Registry::get('dbInfo');
		$row = $db->fetchRow('SELECT `category_name` 
					FROM `tbl_category` WHERE `category_id` = ?',$cid);
		return $row['category_name'];
	}
	
	static function icon($name)
	{
		return '<img src="/static/images/group/icons/'.$name.'" />';
	}
}

?>