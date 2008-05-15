<?php

	class UserModel
	{
		/**
		 * 获取不在本班的校友
		 *
		 */
		static function fetch($class_id,$name)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `uid`,`realName`,`sex` 
								  FROM `vi_class_user` 
								  WHERE `realName` = ? AND (`class_id` != '.$class_id.' OR `class_id` IS NULL)',$name);
		}
	}

?>