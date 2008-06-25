<?php

class Cmd
{		
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