<?php
	/**
	 * 班级成员邀请数据表操作
	 *
	 */
	class InviteModel
	{
		/**
		 * 获取班级邀请信息
		 *
		 * @param int $uid
		 * @return array
		 */
		static function fetchAll($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_invite_id`,`class_name`  
								  FROM `vi_class_invite` 
						   		  WHERE `class_call_id` = ?',$uid);
		}
		
		static function fetchRow($invite_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * 
								  FROM `vi_class_invite` 
						   		  WHERE `class_invite_id` = ?',$invite_id);
		}
	}

?>