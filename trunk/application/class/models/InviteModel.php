<?php
	/**
	 * 班级成员邀请数据表操作
	 *
	 */
	class InviteModel
	{
		static function accept($invite_id, $class_id, $member_id)
		{
			$db = Zend_Registry::get('dbClass');
			// 将用户信息写入班级表
			if($db->insert('tbl_class_member', 
				array('class_id'=>(int)$class_id,'class_member_id'=>(int)$member_id,'class_member_join_time' => time()))>0)
			{
				// 删除邀请
				if($db->delete('tbl_class_invite','`class_invite_id` = '.$invite_id) == 1)
				{
					if($db->update('tbl_class',
						array('class_member_num'=>new Zend_Db_Expr('class_member_num+1')),'class_id = '.(int)$class_id))
					return true;
				}
			}
			return false;
		}
		
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
		
		/**
		 * 获取某一邀请信息的所有信息
		 *
		 * @param int $invite_id
		 * @return array
		 */
		static function fetchRow($invite_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * 
								  FROM `vi_class_invite` 
						   		  WHERE `class_invite_id` = ?',$invite_id);
		}
	}

?>