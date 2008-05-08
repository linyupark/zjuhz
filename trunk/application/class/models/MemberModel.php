<?php

	/**
	 * 所有跟班级成员有关的数据库操作
	 *
	 */
	class MemberModel
	{
		/**
		 * 检查指定memberid是否有对应的班级
		 *
		 * @param int $uid 用户唯一id
		 * @return array / false
		 */
		static function hasClass($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_id`,`class_name`,`class_college`,`class_year`,`class_charge`,`class_member_charge` 
			                      FROM `vi_class_member` 
			                      WHERE `class_member_id` = ?', (int)$uid);
		}
		
		/**
		 * 查看指定的uid是否已经加入了classid班级
		 *
		 * @param int $class_id
		 * @param int $uid
		 * @return array
		 */
		static function isJoined($class_id,$uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_id` FROM `tbl_class_member` 
								  WHERE `class_id` = ? AND `class_member_id` = ?',array($class_id,$uid));
		}
		
		/**
		 * 将指定班级中的某个成员踢出
		 *
		 * @param int $uid
		 * @param int $class_id
		 */
		static function fireOut($uid, $class_id)
		{
			$db = Zend_Registry::get('dbClass');
			if($db->delete('tbl_class_member', array('class_member_id = '.(int)$uid, 'class_id = '.(int)$class_id)) > 0)
			{
				$db->update('tbl_class',array('class_member_num'=>new Zend_Db_Expr('class_member_num-1')),'class_id = '.(int)$class_id);
			}
		}

		/**
		 * 更新班级成员最后访问班级时间
		 *
		 * @param int $uid
		 * @param int $class_id
		 */
		static function lastAccess($uid, $class_id)
		{
			$db = Zend_Registry::get('dbClass');
			$db->update('tbl_class_member', array('class_member_last_access'=>time()), 
											array('class_id='.$class_id,'class_member_id='.$uid));
		}
		
		/**
		 * 返回某班级的管理员数据集
		 *
		 * @param int $class_id
		 * @return array
		 */
		static function getManagers($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_member_id`,`realName` FROM `vi_class_member` 
						          WHERE `class_id` = ? AND `class_member_charge` = 1',$class_id);
		}
		
		/**
		 * 获取在24内登陆过的班级成员
		 *
		 * @param int $class_id 班级id
		 * @param int $time_range 秒数内登陆
		 * @return array 对应的成员姓名和id
		 */
		static function getActivity($class_id, $time_range= 86400)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `realName`,`class_member_id` FROM `vi_class_member` 
							      WHERE `class_id` = ? AND (`class_member_last_access`+'.$time_range.')> ?',array($class_id,time()));
		}
	}

?>